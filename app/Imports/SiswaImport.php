<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\TahunAjaran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class SiswaImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    WithBatchInserts, 
    WithChunkReading, 
    SkipsOnError, 
    SkipsOnFailure, 
    SkipsEmptyRows
{
    use Importable;

    protected $errors = [];
    protected $failures = [];
    protected $successCount = 0;
    protected $kelasMap = [];
    protected $orangTuaMap = [];
    protected $tahunAjaranAktif;

    public function __construct()
    {
        // Cache kelas data untuk performa yang lebih baik
        $this->kelasMap = Kelas::with('tahunAjaran')
            ->get()
            ->keyBy(function($kelas) {
                return strtolower(trim($kelas->nama_kelas));
            });

        // Cache orang tua data
        $this->orangTuaMap = OrangTua::whereIn('status', ['aktif', 'pending'])
            ->get()
            ->keyBy(function($orangTua) {
                return strtolower(trim($orangTua->nama_lengkap));
            });

        // Get active academic year
        $this->tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
    }

    public function model(array $row)
    {
        try {
            // Normalize and clean row data
            $cleanRow = $this->cleanRowData($row);
            
            // Skip jika data kosong atau tidak valid
            if (!$this->isValidRow($cleanRow)) {
                return null;
            }

            // Convert NIS to string and validate
            $nis = $this->convertToString($cleanRow['nis']);
            if (empty($nis)) {
                $this->errors[] = "Baris dengan nama '{$cleanRow['nama']}': NIS tidak valid";
                return null;
            }

            // Cari kelas berdasarkan nama
            $kelas = $this->findKelas($cleanRow['kelas']);
            if (!$kelas) {
                $this->errors[] = "Baris dengan NIS {$nis}: Kelas '{$cleanRow['kelas']}' tidak ditemukan";
                return null;
            }

            // Cari orang tua berdasarkan nama
            $orangTua = $this->findOrangTua($cleanRow['nama_orang_tua']);
            if (!$orangTua) {
                $this->errors[] = "Baris dengan NIS {$nis}: Orang tua '{$cleanRow['nama_orang_tua']}' tidak ditemukan";
                return null;
            }

            // Validasi NIS unik
            if (Siswa::where('nis', $nis)->exists()) {
                $this->errors[] = "Baris dengan NIS {$nis}: NIS sudah digunakan";
                return null;
            }

            // Parse tanggal lahir
            $tanggalLahir = $this->parseTanggalLahir($cleanRow['tanggal_lahir'] ?? null);

            // Validasi jenis kelamin
            $jenisKelamin = $this->normalizeJenisKelamin($cleanRow['jenis_kelamin']);
            if (!$jenisKelamin) {
                $this->errors[] = "Baris dengan NIS {$nis}: Jenis kelamin harus 'Laki-laki' atau 'Perempuan'";
                return null;
            }

            // Tentukan status berdasarkan kelas dan tahun ajaran
            $status = ($kelas->tahunAjaran && $kelas->tahunAjaran->aktif) 
                ? Siswa::STATUS_ACTIVE 
                : Siswa::STATUS_INACTIVE;

            $siswa = new Siswa([
                'nama' => $cleanRow['nama'],
                'nis' => $nis,
                'id_orangtua' => $orangTua->id_orangtua,
                'id_kelas' => $kelas->id_kelas,
                'id_tahun_ajaran' => $kelas->tahunAjaran ? $kelas->tahunAjaran->id_tahun_ajaran : null,
                'tempat_lahir' => $cleanRow['tempat_lahir'] ?? '',
                'tanggal_lahir' => $tanggalLahir,
                'jenis_kelamin' => $jenisKelamin,
                'alamat' => $cleanRow['alamat'] ?? '',
                'status' => $status,
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user() ? Auth::user()->username : 'import_excel',
            ]);

            $this->successCount++;
            return $siswa;

        } catch (\Exception $e) {
            $nis = $this->convertToString($row['nis'] ?? 'unknown');
            $this->errors[] = "Baris dengan NIS {$nis}: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Clean and normalize row data
     */
    private function cleanRowData(array $row): array
    {
        $cleaned = [];
        
        foreach ($row as $key => $value) {
            // Convert key to lowercase and trim
            $cleanKey = strtolower(trim($key));
            
            // Clean value - handle null, convert to string, trim
            if ($value === null || $value === '') {
                $cleaned[$cleanKey] = '';
            } else {
                $cleaned[$cleanKey] = trim((string) $value);
            }
        }
        
        return $cleaned;
    }

    /**
     * Check if row has valid required data
     */
    private function isValidRow(array $row): bool
    {
        $requiredFields = ['nama', 'nis', 'kelas', 'nama_orang_tua', 'jenis_kelamin'];
        
        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Convert value to string, handling Excel numeric values
     */
    private function convertToString($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        
        // If it's a number, convert to string without scientific notation
        if (is_numeric($value)) {
            return number_format($value, 0, '', '');
        }
        
        return trim((string) $value);
    }

    /**
     * Parse tanggal lahir from various formats
     */
    private function parseTanggalLahir($tanggalLahir): ?string
    {
        if (empty($tanggalLahir)) {
            return null;
        }

        try {
            // Handle Excel date serial number
            if (is_numeric($tanggalLahir)) {
                return Date::excelToDateTimeObject($tanggalLahir)->format('Y-m-d');
            }
            
            // Handle string dates
            $date = Carbon::parse($tanggalLahir);
            return $date->format('Y-m-d');
            
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Normalize jenis kelamin
     */
    private function normalizeJenisKelamin($jenisKelamin): ?string
    {
        if (empty($jenisKelamin)) {
            return null;
        }
        
        $jenisKelamin = strtolower(trim($jenisKelamin));
        
        if (in_array($jenisKelamin, ['laki-laki', 'laki', 'l', 'male', 'pria'])) {
            return 'Laki-laki';
        }
        
        if (in_array($jenisKelamin, ['perempuan', 'wanita', 'p', 'female', 'cewe'])) {
            return 'Perempuan';
        }
        
        return null;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:20',
            'kelas' => 'required|string',
            'nama_orang_tua' => 'required|string',
            'jenis_kelamin' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama siswa wajib diisi',
            'nis.required' => 'NIS wajib diisi',
            'kelas.required' => 'Kelas wajib diisi',
            'nama_orang_tua.required' => 'Nama orang tua wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
        ];
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function onError(Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            // Only process failures for rows with actual data
            $values = $failure->values();
            if ($this->isValidRow($this->cleanRowData($values))) {
                $this->failures[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $values,
                ];
            }
        }
    }

    private function findKelas($namaKelas)
    {
        if (empty($namaKelas)) {
            return null;
        }
        
        $namaKelas = strtolower(trim($namaKelas));
        
        // Cari exact match dulu
        $exactMatch = $this->kelasMap->get($namaKelas);
        if ($exactMatch) {
            return $exactMatch;
        }
        
        // Jika tidak ada exact match, cari yang mirip
        foreach ($this->kelasMap as $key => $kelas) {
            if (strpos($key, $namaKelas) !== false || strpos($namaKelas, $key) !== false) {
                return $kelas;
            }
        }
        
        return null;
    }

    private function findOrangTua($namaOrangTua)
    {
        if (empty($namaOrangTua)) {
            return null;
        }
        
        $namaOrangTua = strtolower(trim($namaOrangTua));
        
        // Cari exact match dulu
        $exactMatch = $this->orangTuaMap->get($namaOrangTua);
        if ($exactMatch) {
            return $exactMatch;
        }
        
        // Jika tidak ada exact match, cari yang mirip
        foreach ($this->orangTuaMap as $key => $orangTua) {
            if (strpos($key, $namaOrangTua) !== false || strpos($namaOrangTua, $key) !== false) {
                return $orangTua;
            }
        }
        
        return null;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getFailures()
    {
        return $this->failures;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getAvailableKelas()
    {
        return $this->kelasMap->map(function($kelas) {
            return [
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat,
                'tahun_ajaran' => $kelas->tahunAjaran ? $kelas->tahunAjaran->nama_tahun_ajaran : 'Tidak ada',
                'status' => $kelas->tahunAjaran && $kelas->tahunAjaran->aktif ? 'Aktif' : 'Non-Aktif'
            ];
        })->values();
    }

    public function getAvailableOrangTua()
    {
        return $this->orangTuaMap->map(function($orangTua) {
            return [
                'nama_lengkap' => $orangTua->nama_lengkap,
                'status' => ucfirst($orangTua->status),
                'nomor_telepon' => $orangTua->nomor_telepon ?? '-'
            ];
        })->values();
    }
}
