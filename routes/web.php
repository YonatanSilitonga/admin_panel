<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratIzinController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\SiswaImportController;

// Menampilkan formulir login
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Memproses data login
Route::post('/login', [AuthController::class, 'processLogin']);

// Protected Routes - pakai middleware auth (session)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/admin/beranda', [DashboardController::class, 'index'])->name('admin.beranda');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Users
    Route::resource('users', UserController::class);

    // Guru
    Route::resource('guru', GuruController::class);
    Route::get('guru/export/pdf', [GuruController::class, 'exportPdf'])->name('guru.export.pdf');
    Route::get('guru/export/excel', [GuruController::class, 'exportExcel'])->name('guru.export.excel');

    // Siswa
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa/kelas/{kelasId}', [SiswaController::class, 'getByKelas']);
    Route::get('/siswa/orang-tua/{orangTuaId}', [SiswaController::class, 'getByOrangTua']);
    Route::post('siswa/{id}/update-status', [SiswaController::class, 'updateStatus']);

    // Export Siswa
    Route::get('/siswa/export/pdf', [SiswaController::class, 'exportPdf'])->name('siswa.export.pdf');
    Route::get('/siswa/export/excel', [SiswaController::class, 'exportExcel'])->name('siswa.export.excel');
    
    // Import Siswa - Enhanced
    Route::get('/siswa/import/preview', [SiswaImportController::class, 'showImportPreview'])->name('siswa.import.preview');
    Route::post('/siswa/import/process', [SiswaImportController::class, 'processImport'])->name('siswa.import.process');
    Route::get('/siswa/import/template', [SiswaImportController::class, 'downloadTemplate'])->name('siswa.import.template');
    Route::post('/siswa/import/validate', [SiswaImportController::class, 'validateImportFile'])->name('siswa.import.validate');
    
    // Import Siswa - Legacy (untuk backward compatibility)
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import.excel');

    // Orang Tua
    Route::resource('orang-tua', OrangTuaController::class);
    Route::get('orang-tua/export/pdf', [OrangTuaController::class, 'exportPdf'])->name('orang-tua.export.pdf');
    Route::get('orang-tua/export/excel', [OrangTuaController::class, 'exportExcel'])->name('orang-tua.export.excel');
    Route::post('orang-tua/{id}/update-status', [OrangTuaController::class, 'updateStatus']);
    Route::get('/orang-tua/{id}/anak', [OrangTuaController::class, 'getAnak']);
    
    // Halaman detail berdasarkan kelas yang dipilih
    Route::get('/orang-tua/kelas/{id_kelas}', [OrangTuaController::class, 'showByKelas'])->name('orang-tua.kelas');

    // Kelas
    Route::resource('kelas', KelasController::class);
    Route::get('kelas/{id}/students', [KelasController::class, 'getStudents']);
    Route::post('kelas/{id}/update-student-statuses', [KelasController::class, 'updateStudentStatuses']);
    Route::get('kelas/tahun-ajaran/{tahunAjaranId}', [KelasController::class, 'getByTahunAjaran']);
    Route::get('kelas/active', [KelasController::class, 'getActiveClasses']);

    // Tahun Ajaran
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::get('tahun-ajaran/{id}/set-active', [TahunAjaranController::class, 'setActive'])->name('tahun-ajaran.set-active');
    Route::get('tahun-ajaran/active', [TahunAjaranController::class, 'getActive']);

    // Mata Pelajaran
    Route::resource('mata-pelajaran', MataPelajaranController::class);
    Route::get('/mata-pelajaran/{id}/jumlah-guru', [MataPelajaranController::class, 'getJumlahGuru']);
    Route::get('/mata-pelajaran/{id}/guru-pengampu', [MataPelajaranController::class, 'getGuruPengampu']);

    // Jadwal Pelajaran
    Route::resource('jadwal-pelajaran', JadwalPelajaranController::class);
    Route::post('/jadwal-pelajaran/store-massal', [JadwalPelajaranController::class, 'storeMassal'])->name('jadwal-pelajaran.store-massal');
    Route::post('/jadwal-pelajaran/check-conflicts', [JadwalPelajaranController::class, 'checkConflicts']);
    Route::post('/jadwal-pelajaran/check-conflicts-massal', [JadwalPelajaranController::class, 'checkConflictsMassal']);
    Route::get('/jadwal-pelajaran/guru-by-mapel/{idMataPelajaran}', [JadwalPelajaranController::class, 'getGuruByMataPelajaran']);
    
    // Absensi
    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi/siswa/{siswaId}', [AbsensiController::class, 'getBySiswa']);
    Route::get('/absensi/jadwal/{jadwalId}/{tanggal?}', [AbsensiController::class, 'getByJadwal']);
    Route::post('/absensi/bulk', [AbsensiController::class, 'createBulk']);

    // Surat Izin
    Route::resource('surat-izin', SuratIzinController::class);
    Route::get('/surat-izin/siswa/{siswaId}', [SuratIzinController::class, 'getBySiswa']);
    Route::get('/surat-izin/guru/{guruId}', [SuratIzinController::class, 'getByGuru']);
    Route::put('/surat-izin/{id}/approve', [SuratIzinController::class, 'approve']);
    Route::put('/surat-izin/{id}/reject', [SuratIzinController::class, 'reject']);
    
    // Rekapitulasi
    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index']);
    Route::get('/rekapitulasi/kelas/{id_kelas}', [RekapitulasiController::class, 'showByKelas']);
    Route::get('/rekapitulasi/export/pdf', [RekapitulasiController::class, 'exportPdf'])->name('rekapitulasi.export.pdf');
    Route::get('/rekapitulasi/export/excel', [RekapitulasiController::class, 'exportExcel'])->name('rekapitulasi.export.excel');
});
