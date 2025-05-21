<?php

namespace App\Http\Controllers\API;


use App\Models\User;
use App\Models\OrangTua;
use App\Models\SuratIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SuratIzinController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'nullable|exists:siswa,id_siswa',
            'id_orangtua' => 'nullable|exists:orangtua,id_orangtua',
            'status' => 'nullable|in:menunggu,disetujui,ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = SuratIzin::with(['siswa', 'orangtua']);

        if ($request->has('id_siswa')) {
            $query->where('id_siswa', $request->id_siswa);
        }

        if ($request->has('id_orangtua')) {
            $query->where('id_orangtua', $request->id_orangtua);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $suratIzin = $query->orderBy('dibuat_pada', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $suratIzin
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id_siswa' => 'required|exists:siswa,id_siswa',
    //         'id_orangtua' => 'required|exists:orangtua,id_orangtua',
    //         'jenis' => 'required|in:sakit,izin',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'alasan' => 'required|string',
    //         'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $suratIzin = new SuratIzin();
    //     $suratIzin->id_siswa = $request->id_siswa;
    //     $suratIzin->id_orangtua = $request->id_orangtua;
    //     $suratIzin->jenis = $request->jenis;
    //     $suratIzin->tanggal_mulai = $request->tanggal_mulai;
    //     $suratIzin->tanggal_selesai = $request->tanggal_selesai;
    //     $suratIzin->alasan = $request->alasan;
    //     $suratIzin->status = 'menunggu';
    //     $suratIzin->dibuat_oleh = $request->user()->username;

    //     if ($request->hasFile('file_lampiran')) {
    //         $file = $request->file('file_lampiran');
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $filePath = $file->storeAs('surat_izin', $fileName, 'public');
    //         $suratIzin->file_lampiran = $filePath;
    //     }

    //     $suratIzin->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Surat izin berhasil dibuat',
    //         'data' => $suratIzin
    //     ], 201);
    // }

    public function store(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id_user',
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'jenis' => 'required|in:sakit,izin',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari orangtua berdasarkan id_user
        $orangtua = OrangTua::where('id_user', $request->id_user)->first();

        if (!$orangtua) {
            return response()->json([
                'success' => false,
                'message' => 'Data orang tua tidak ditemukan untuk user ini'
            ], 404);
        }

        // Handle file upload jika ada
        $fileName = null;
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $fileName = $file->store('lampiran_surat_izin', 'public');
        }

        // Simpan surat izin
        $surat = SuratIzin::create([
            'id_siswa' => $request->id_siswa,
            'id_orangtua' => $orangtua->id_orangtua,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'file_lampiran' => $fileName,
            'status' => 'menunggu',
            'dibuat_oleh' => $request->id_user,
            'diperbarui_oleh' => $request->id_user,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil disimpan',
            'data' => $surat
        ]);
    }
    public function show($id)
    {
        $suratIzin = SuratIzin::with(['siswa', 'orangtua'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $suratIzin
        ]);
    }

    public function update(Request $request, $id)
    {
        $suratIzin = SuratIzin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'jenis' => 'nullable|in:sakit,izin',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'alasan' => 'nullable|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:menunggu,disetujui,ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('jenis')) {
            $suratIzin->jenis = $request->jenis;
        }

        if ($request->has('tanggal_mulai')) {
            $suratIzin->tanggal_mulai = $request->tanggal_mulai;
        }

        if ($request->has('tanggal_selesai')) {
            $suratIzin->tanggal_selesai = $request->tanggal_selesai;
        }

        if ($request->has('alasan')) {
            $suratIzin->alasan = $request->alasan;
        }

        if ($request->has('status')) {
            $suratIzin->status = $request->status;
        }

        if ($request->hasFile('file_lampiran')) {
            // Delete old file if exists
            if ($suratIzin->file_lampiran) {
                Storage::disk('public')->delete($suratIzin->file_lampiran);
            }

            $file = $request->file('file_lampiran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_izin', $fileName, 'public');
            $suratIzin->file_lampiran = $filePath;
        }

        $suratIzin->diperbarui_oleh = $request->user()->username;
        $suratIzin->save();

        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil diperbarui',
            'data' => $suratIzin
        ]);
    }

    public function destroy($id)
    {
        $suratIzin = SuratIzin::findOrFail($id);

        // Delete file if exists
        if ($suratIzin->file_lampiran) {
            Storage::disk('public')->delete($suratIzin->file_lampiran);
        }

        $suratIzin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,disetujui,ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $suratIzin = SuratIzin::findOrFail($id);
        $suratIzin->status = $request->status;
        $suratIzin->diperbarui_oleh = $request->user()->username;
        $suratIzin->save();

        return response()->json([
            'success' => true,
            'message' => 'Status surat izin berhasil diperbarui',
            'data' => $suratIzin
        ]);
    }

    // File controller
    public function viewFile($path)
    {
        // Bersihkan path
        $cleanPath = str_replace(['..'], '', $path);

        // Path yang benar
        $filePath = storage_path("app/public/" . $cleanPath);

        if (file_exists($filePath)) {
            return response()->file($filePath);
        }

        return response()->json(['error' => 'File tidak ditemukan'], 404);
    }

    public function updateStatusSurat(Request $request, $id_user, $id_surat)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak',
        ]);

        // Ambil user beserta guru dan kelasnya
        $user = User::with('guru.kelas.siswa')->where('id_user', $id_user)->first();

        if (!$user || !$user->guru || !$user->guru->kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak valid.',
            ], 404);
        }

        $surat = SuratIzin::where('id_surat_izin', $id_surat)->first();

        if (!$surat) {
            return response()->json([
                'success' => false,
                'message' => 'Surat tidak ditemukan.',
            ], 404);
        }

        // Pastikan surat berada dalam pengawasan guru
        $kelas = $user->guru->kelas;
        $siswaIds = $kelas->siswa->pluck('id_siswa')->toArray();

        if (!in_array($surat->id_siswa, $siswaIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Surat tidak termasuk dalam pengawasan guru ini.',
            ], 403);
        }

        // Update status surat
        $surat->status = $request->status;
        $surat->diperbarui_oleh = $user->guru->nama_lengkap ?? 'Guru Tidak Dikenal'; // ⬅️ tambahan ini
        $surat->save();

        return response()->json([
            'success' => true,
            'message' => 'Status surat berhasil diperbarui.',
            'data' => [
                'id_surat_izin' => $surat->id_surat_izin,
                'status' => $surat->status,
            ],
        ]);
    }

    // Tambahkan route baru untuk mengakses file dengan path lengkap
    public function viewFileByPath(Request $request)
    {
        $filePath = $request->input('path');

        if (empty($filePath)) {
            return response()->json([
                'error' => 'No file path provided'
            ], 400);
        }

        // Decode URL-encoded path
        $filePath = urldecode($filePath);

        // Sanitasi path untuk keamanan
        $filePath = str_replace('..', '', $filePath);

        // Log untuk debugging
        Log::info('Accessing file by path: ' . $filePath);

        // Coba beberapa kemungkinan lokasi file
        $possiblePaths = [
            public_path("storage/{$filePath}"),
            storage_path("app/public/{$filePath}"),
            $filePath
        ];

        // Cek semua kemungkinan path
        $fullPath = null;
        foreach ($possiblePaths as $path) {
            Log::info('Checking path: ' . $path);
            if (file_exists($path)) {
                $fullPath = $path;
                Log::info('File found at: ' . $fullPath);
                break;
            }
        }

        if (!$fullPath) {
            Log::error('File not found: ' . $filePath);
            return response()->json([
                'error' => 'File not found'
            ], 404);
        }

        // Tambahkan header untuk menangani CORS
        return response()->file($fullPath, [
            'Content-Type' => mime_content_type($fullPath),
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
        ]);
    }
}
