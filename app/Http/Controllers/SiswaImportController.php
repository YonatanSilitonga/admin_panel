<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\TahunAjaran;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class SiswaImportController extends Controller
{
    /**
     * Show import preview page with available data
     */
    public function showImportPreview()
    {
        try {
            $availableKelas = Kelas::with('tahunAjaran', 'guru')
                ->orderBy('tingkat')
                ->orderBy('nama_kelas')
                ->get()
                ->map(function($kelas) {
                    return [
                        'nama_kelas' => $kelas->nama_kelas,
                        'tingkat' => $kelas->tingkat,
                        'tahun_ajaran' => $kelas->tahunAjaran ? $kelas->tahunAjaran->nama_tahun_ajaran : 'Tidak ada',
                        'status' => $kelas->tahunAjaran && $kelas->tahunAjaran->aktif ? 'Aktif' : 'Non-Aktif',
                        'wali_kelas' => $kelas->guru ? $kelas->guru->nama_lengkap : 'Belum ada',
                        'jumlah_siswa' => $kelas->siswa()->count()
                    ];
                });

            $availableOrangTua = OrangTua::whereIn('status', ['aktif', 'pending'])
                ->orderBy('nama_lengkap')
                ->get()
                ->map(function($orangTua) {
                    return [
                        'nama_lengkap' => $orangTua->nama_lengkap,
                        'status' => ucfirst($orangTua->status),
                        'nomor_telepon' => $orangTua->nomor_telepon ?? '-',
                        'jumlah_anak' => $orangTua->siswa()->count()
                    ];
                });

            $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();

            return view('admin.pages.siswa.import_preview', compact(
                'availableKelas', 
                'availableOrangTua', 
                'tahunAjaranAktif'
            ));
        } catch (\Exception $e) {
            Log::error('Error in showImportPreview: ' . $e->getMessage());
            return redirect()->route('siswa.index')
                ->with('error', 'Gagal memuat halaman import: ' . $e->getMessage());
        }
    }

    /**
     * Validate import file before processing
     */
    public function validateImportFile(Request $request)
    {
        Log::info('Validate import file request received');
        
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:2048'
            ]);

            Log::info('File validation passed');

            // Read file content
            $data = Excel::toArray(new SiswaImport(), $request->file('file'));
            
            if (empty($data) || empty($data[0])) {
                Log::warning('File is empty or invalid');
                return response()->json([
                    'success' => false,
                    'message' => 'File kosong atau tidak valid'
                ]);
            }

            // Count valid data rows (skip empty rows)
            $validRows = 0;
            $headers = array_keys($data[0][0]);
            
            for ($i = 1; $i < count($data[0]); $i++) {
                $row = $data[0][$i];
                $hasData = false;
                
                // Check if row has any non-empty required data
                foreach (['nama', 'nis', 'kelas', 'nama_orang_tua', 'jenis_kelamin'] as $field) {
                    if (!empty($row[$field])) {
                        $hasData = true;
                        break;
                    }
                }
                
                if ($hasData) {
                    $validRows++;
                }
            }

            if ($validRows === 0) {
                Log::warning('No valid data rows found');
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak berisi data siswa yang valid'
                ]);
            }

            $requiredHeaders = ['nama', 'nis', 'kelas', 'nama_orang_tua', 'jenis_kelamin'];
            $missingHeaders = array_diff($requiredHeaders, $headers);
            
            if (!empty($missingHeaders)) {
                Log::warning('Missing headers: ' . implode(', ', $missingHeaders));
                return response()->json([
                    'success' => false,
                    'message' => 'Header yang hilang: ' . implode(', ', $missingHeaders)
                ]);
            }
            
            Log::info('File validation successful');
            return response()->json([
                'success' => true,
                'message' => 'File valid',
                'row_count' => $validRows,
                'headers' => $headers
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error validating file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error validating file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process the import
     */
    public function processImport(Request $request)
    {
        Log::info('Process import request received');
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            DB::beginTransaction();
            
            $import = new SiswaImport();
            Excel::import($import, $request->file('file'));
            
            // Update parent statuses after import
            $this->updateParentStatusesAfterImport();
            
            DB::commit();
            
            $errors = $import->getErrors();
            $failures = $import->getFailures();
            $successCount = $import->getSuccessCount();
            
            Log::info("Import completed - Success: {$successCount}, Errors: " . count($errors) . ", Failures: " . count($failures));
            
            if (empty($errors) && empty($failures)) {
                return redirect()->route('siswa.index')
                    ->with('success', "Berhasil mengimport {$successCount} data siswa!");
            } else {
                $message = "Import selesai dengan {$successCount} data berhasil.";
                if (!empty($errors) || !empty($failures)) {
                    $message .= " Terdapat beberapa error yang perlu diperbaiki.";
                }
                
                return redirect()->route('siswa.import.preview')
                    ->with('warning', $message)
                    ->with('import_errors', $errors)
                    ->with('import_failures', $failures)
                    ->with('success_count', $successCount);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing import: ' . $e->getMessage());
            return redirect()->route('siswa.import.preview')
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    /**
     * Generate dynamic template with current data
     */
    public function downloadTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Data Siswa');
            
            // Set headers
            $headers = [
                'A1' => 'nama',
                'B1' => 'nis', 
                'C1' => 'kelas',
                'D1' => 'nama_orang_tua',
                'E1' => 'jenis_kelamin',
                'F1' => 'tempat_lahir',
                'G1' => 'tanggal_lahir',
                'H1' => 'alamat'
            ];
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }
            
            // Style headers
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '28A745']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ];
            
            $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
            
            // Add sample data with real class and parent names
            $kelasContoh = Kelas::with('tahunAjaran')->first();
            $orangTuaContoh = OrangTua::whereIn('status', ['aktif', 'pending'])->first();
            
            $sampleData = [
                [
                    'Ahmad Rizki', 
                    '2024001', 
                    $kelasContoh ? $kelasContoh->nama_kelas : 'X IPA 1', 
                    $orangTuaContoh ? $orangTuaContoh->nama_lengkap : 'Budi Santoso', 
                    'Laki-laki', 
                    'Jakarta', 
                    '2005-01-15', 
                    'Jl. Merdeka No. 123'
                ],
                [
                    'Siti Nurhaliza', 
                    '2024002', 
                    $kelasContoh ? $kelasContoh->nama_kelas : 'X IPS 1', 
                    $orangTuaContoh ? $orangTuaContoh->nama_lengkap : 'Siti Aminah', 
                    'Perempuan', 
                    'Bandung', 
                    '2005-03-20', 
                    'Jl. Sudirman No. 456'
                ]
            ];
            
            $row = 2;
            foreach ($sampleData as $data) {
                $col = 'A';
                foreach ($data as $index => $value) {
                    // Force NIS to be text format
                    if ($index === 1) { // NIS column
                        $sheet->setCellValueExplicit($col . $row, $value, DataType::TYPE_STRING);
                    } else {
                        $sheet->setCellValue($col . $row, $value);
                    }
                    $col++;
                }
                $row++;
            }
            
            // Format NIS column as text
            $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode('@');
            
            // Auto-size columns
            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Add instructions sheet
            $instructionSheet = $spreadsheet->createSheet();
            $instructionSheet->setTitle('Petunjuk Penggunaan');
            
            // Header petunjuk
            $instructionSheet->setCellValue('A1', 'PETUNJUK PENGGUNAAN TEMPLATE IMPORT SISWA');
            $instructionSheet->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '28A745']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E8']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            
            $row = 3;
            
            // Kolom wajib
            $instructionSheet->setCellValue('A' . $row, '1. KOLOM YANG WAJIB DIISI:');
            $instructionSheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'DC3545']],
            ]);
            $row++;
            
            $wajibFields = [
                'nama' => 'Nama lengkap siswa (contoh: Ahmad Rizki)',
                'nis' => 'Nomor Induk Siswa - harus unik, format TEXT (contoh: 2024001)',
                'kelas' => 'Nama kelas sesuai daftar yang tersedia',
                'nama_orang_tua' => 'Nama lengkap orang tua sesuai daftar yang tersedia',
                'jenis_kelamin' => 'Laki-laki atau Perempuan (huruf besar/kecil bebas)'
            ];
            
            foreach ($wajibFields as $field => $desc) {
                $instructionSheet->setCellValue('A' . $row, "   • {$field}: {$desc}");
                $instructionSheet->getStyle('A' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '495057']],
                ]);
                $row++;
            }
            
            $row++;
            
            // Kolom opsional
            $instructionSheet->setCellValue('A' . $row, '2. KOLOM OPSIONAL:');
            $instructionSheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFC107']],
            ]);
            $row++;
            
            $opsionalFields = [
                'tempat_lahir' => 'Tempat lahir siswa (contoh: Jakarta)',
                'tanggal_lahir' => 'Format: YYYY-MM-DD atau DD/MM/YYYY (contoh: 2005-01-15)',
                'alamat' => 'Alamat lengkap siswa'
            ];
            
            foreach ($opsionalFields as $field => $desc) {
                $instructionSheet->setCellValue('A' . $row, "   • {$field}: {$desc}");
                $instructionSheet->getStyle('A' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '6C757D']],
                ]);
                $row++;
            }
            
            $row += 2;
            
            // Catatan penting
            $instructionSheet->setCellValue('A' . $row, '3. CATATAN PENTING:');
            $instructionSheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'DC3545']],
            ]);
            $row++;
            
            $catatan = [
                '• HAPUS BARIS CONTOH (baris 2-3) sebelum mengimport data asli',
                '• NIS harus dalam format TEXT, bukan angka',
                '• Pastikan tidak ada baris kosong di antara data',
                '• Status siswa akan otomatis ditentukan berdasarkan status tahun ajaran kelas',
                '• Pastikan nama kelas dan nama orang tua ditulis PERSIS seperti di daftar',
                '• NIS harus unik, tidak boleh sama dengan siswa yang sudah ada'
            ];
            
            foreach ($catatan as $note) {
                $instructionSheet->setCellValue('A' . $row, $note);
                $instructionSheet->getStyle('A' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '495057']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8F9FA']],
                ]);
                $row++;
            }
            
            $row += 2;
            
            // Daftar kelas
            $instructionSheet->setCellValue('A' . $row, '4. DAFTAR KELAS YANG TERSEDIA:');
            $instructionSheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '007BFF']],
            ]);
            $row++;
            
            $availableKelas = Kelas::with('tahunAjaran')->orderBy('tingkat')->orderBy('nama_kelas')->get();
            foreach ($availableKelas as $kelas) {
                $status = $kelas->tahunAjaran && $kelas->tahunAjaran->aktif ? 'AKTIF' : 'NON-AKTIF';
                $tahunAjaran = $kelas->tahunAjaran ? $kelas->tahunAjaran->nama_tahun_ajaran : 'Tidak ada';
                
                $instructionSheet->setCellValue('A' . $row, $kelas->nama_kelas);
                $instructionSheet->setCellValue('B' . $row, "({$tahunAjaran}) - {$status}");
                
                // Style untuk kelas aktif
                if ($status === 'AKTIF') {
                    $instructionSheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '28A745']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D4EDDA']],
                    ]);
                } else {
                    $instructionSheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => '6C757D']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8F9FA']],
                    ]);
                }
                $row++;
            }
            
            $row += 2;
            
            // Daftar orang tua
            $instructionSheet->setCellValue('A' . $row, '5. DAFTAR ORANG TUA YANG TERSEDIA:');
            $instructionSheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '6F42C1']],
            ]);
            $row++;
            
            $availableOrangTua = OrangTua::whereIn('status', ['aktif', 'pending'])->orderBy('nama_lengkap')->get();
            foreach ($availableOrangTua as $orangTua) {
                $status = ucfirst($orangTua->status);
                $phone = $orangTua->nomor_telepon ? " - {$orangTua->nomor_telepon}" : '';
                
                $instructionSheet->setCellValue('A' . $row, $orangTua->nama_lengkap);
                $instructionSheet->setCellValue('B' . $row, "({$status}){$phone}");
                
                // Style untuk orang tua aktif
                if ($orangTua->status === 'aktif') {
                    $instructionSheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '28A745']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D4EDDA']],
                    ]);
                } else {
                    $instructionSheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'FFC107']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF3CD']],
                    ]);
                }
                $row++;
            }
            
            // Set column widths
            $instructionSheet->getColumnDimension('A')->setWidth(60);
            $instructionSheet->getColumnDimension('B')->setWidth(30);
            
            // Set active sheet back to data sheet
            $spreadsheet->setActiveSheetIndex(0);
            
            // Generate filename
            $filename = 'template_import_siswa_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Create writer and download
            $writer = new Xlsx($spreadsheet);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            Log::error('Error generating template: ' . $e->getMessage());
            return redirect()->route('siswa.import.preview')
                ->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }

    /**
     * Update parent statuses after import
     */
    private function updateParentStatusesAfterImport()
    {
        $parents = OrangTua::whereHas('siswa')->get();
        
        foreach ($parents as $parent) {
            $parent->updateStatusBasedOnChildren();
        }
    }
}
