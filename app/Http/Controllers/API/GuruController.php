<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\SuratIzin;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user(); // user yang login

        $guru = \App\Models\Guru::with(['user', 'mataPelajaran', 'kelas'])
            ->where('id_user', $user->id_user)
            ->first();

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $guru->nama_lengkap, // ambil dari relasi user
                'nip' => $guru->nip,          // dari tabel guru
            ],
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:guru,nip',
            'bidang_studi' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Buat user terlebih dahulu
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'id_role' => 2, // Role guru
                'nomor_telepon' => $request->nomor_telepon,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Buat data guru
            $guru = Guru::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'bidang_studi' => $request->bidang_studi,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Jika ada mata pelajaran, tambahkan relasi
            if ($request->has('mata_pelajaran') && is_array($request->mata_pelajaran)) {
                $mataPelajaranData = [];
                foreach ($request->mata_pelajaran as $idMataPelajaran) {
                    $mataPelajaranData[$idMataPelajaran] = [
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'API',
                    ];
                }
                $guru->mataPelajaran()->attach($mataPelajaranData);
            }

            DB::commit();

            // Load relasi untuk response
            $guru->load(['user', 'mataPelajaran']);

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil ditambahkan',
                'data' => $guru,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $guru = Guru::with(['user', 'mataPelajaran', 'jadwal.kelas', 'jadwal.mataPelajaran'])->find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $guru,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::with('user')->find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'string|max:255',
            'nip' => 'nullable|string|max:50|unique:guru,nip,' . $id . ',id_guru',
            'bidang_studi' => 'nullable|string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $guru->id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update user data
            $userData = [];

            if ($request->has('username')) {
                $userData['username'] = $request->username;
            }

            if ($request->has('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->has('nomor_telepon')) {
                $userData['nomor_telepon'] = $request->nomor_telepon;
            }

            if (!empty($userData)) {
                $userData['diperbarui_pada'] = now();
                $userData['diperbarui_oleh'] = 'API';
                $guru->user->update($userData);
            }

            // Update guru data
            $guruData = [];

            if ($request->has('nama_lengkap')) {
                $guruData['nama_lengkap'] = $request->nama_lengkap;
            }

            if ($request->has('nip')) {
                $guruData['nip'] = $request->nip;
            }

            if ($request->has('bidang_studi')) {
                $guruData['bidang_studi'] = $request->bidang_studi;
            }

            if (!empty($guruData)) {
                $guruData['diperbarui_pada'] = now();
                $guruData['diperbarui_oleh'] = 'API';
                $guru->update($guruData);
            }

            // Update mata pelajaran jika ada
            if ($request->has('mata_pelajaran')) {
                $mataPelajaranData = [];
                foreach ($request->mata_pelajaran as $idMataPelajaran) {
                    $mataPelajaranData[$idMataPelajaran] = [
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'API',
                    ];
                }
                $guru->mataPelajaran()->sync($mataPelajaranData);
            }

            DB::commit();

            // Refresh model untuk mendapatkan data terbaru
            $guru = Guru::with(['user', 'mataPelajaran'])->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil diperbarui',
                'data' => $guru,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Simpan id_user untuk menghapus user setelah guru dihapus
            $idUser = $guru->id_user;

            // Hapus guru
            $guru->delete();

            // Hapus user terkait
            User::where('id_user', $idUser)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Get jadwal mengajar guru.
     */
    public function getJadwal($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
        }

        $guru = $user->guru;
        if (!$guru) {
            return response()->json(['message' => 'Guru tidak ditemukan.'], 404);
        }

        // Ambil hari sekarang dalam format lowercase: senin, selasa, dll.
        $hariIni = strtolower(Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('l')); // Gunakan zona waktu Jakarta

        // Ambil hanya jadwal hari ini
        $jadwal = Jadwal::where('id_guru', $guru->id_guru)
            ->whereRaw('LOWER(hari) = ?', [$hariIni]) // cocokkan dengan lowercase
            ->with('kelas.siswa', 'mataPelajaran')
            ->get();
        // 🔽 Sort jadwal berdasarkan waktu_mulai paling awal
        $jadwal = $jadwal->sortBy(function ($item) {
            return Carbon::parse($item->waktu_mulai);
        })->values(); // reset keys agar jadi array numerik

        $now = now('Asia/Jakarta'); // Waktu sekarang untuk pengujian
        $now->addHour(); // Menambahkan satu jam ke waktu sekarang

        $formattedJadwal = $jadwal->map(function ($item) use ($now) {
            // Pastikan waktu mulai dan selesai adalah objek Carbon
            $waktuMulai = Carbon::parse($item->waktu_mulai);
            $waktuSelesai = Carbon::parse($item->waktu_selesai);

            // Menentukan status jadwal berdasarkan waktu sekarang
            $status = $this->getStatusJadwal($waktuMulai, $waktuSelesai);

            return [
                'id_jadwal' => $item->id_jadwal,
                'kelas' => $item->kelas->nama_kelas,
                'total_siswa' => $item->kelas->siswa->count(), // Tambahkan jumlah siswa
                'id_kelas' => $item->kelas->id_kelas,
                'mata_pelajaran' => $item->mataPelajaran->nama,
                'hari' => $item->hari,
                'waktu' => $waktuMulai->format('H:i') . ' - ' . $waktuSelesai->format('H:i'),
                'jam_mulai' => $waktuMulai->format('H:i'),
                'jam_selesai' => $waktuSelesai->format('H:i'),
                'status' => $status,
                'color' => $this->getStatusColor($status),
                'waktu_sekarang' => $now->format('Y-m-d H:i:s') // Menambahkan waktu sekarang ke data
            ];
        });

        return response()->json(['data' => $formattedJadwal]);
    }

    // Metode untuk menentukan status jadwal berdasarkan waktu sekarang
    private function getStatusJadwal($waktuMulai, $waktuSelesai)
    {
        $now = now('Asia/Jakarta'); // Menggunakan waktu Indonesia
        $now->addHour(); // Menambahkan satu jam ke waktu sekarang

        if ($now->isBefore($waktuMulai)) {
            return 'Mendatang';
        }

        if ($now->isBetween($waktuMulai, $waktuSelesai)) {
            return 'Sedang Berjalan';
        }

        return 'Selesai';
    }

    // Metode untuk menentukan warna berdasarkan status jadwal
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'Sedang Berjalan':
                return '#1976D2'; // Warna biru
            case 'Mendatang':
                return '#BDBDBD'; // Warna abu-abu
            case 'Selesai':
                return '#1B3C2F'; // Warna hijau
            default:
                return '#FFFFFF'; // Default
        }
    }

    public function getProfile($id)
    {
        // Mencari pengguna berdasarkan ID_user
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
        }

        // Asumsi bahwa ID_user memiliki relasi dengan Guru
        $guru = $user->guru; // Pastikan relasi 'guru' didefinisikan di model User

        if (!$guru) {
            return response()->json(['message' => 'Data guru tidak ditemukan.'], 404);
        }

        return response()->json([
            'user' => [
                'nama_lengkap' => $guru->nama_lengkap,
                'nip' => $guru->nip,
                'nomor_telepon' => $guru->nomor_telepon,
                'bidang_studi' => $guru->bidang_studi,
            ]
        ], 200);
    }
    public function getNotifikasiSuratIzin($id_user)
    {
        // Ambil user dengan relasi guru -> kelas -> siswa -> suratIzin
        $user = User::with('guru.kelas.siswa.suratIzin')->find($id_user);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
                'data' => ['data' => []]
            ], 404);
        }

        $guru = $user->guru;

        if (!$guru || !$guru->kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru atau kelas tidak ditemukan.',
                'data' => ['data' => []]
            ], 404);
        }

        $notifikasi = [];

        // Jika guru->kelas adalah koleksi, lakukan iterasi
        $kelasList = $guru->kelas;

        // Tangani jika 'kelas' adalah satuan (bukan collection)
        if (!$kelasList instanceof \Illuminate\Support\Collection) {
            $kelasList = collect([$kelasList]);
        }

        foreach ($kelasList as $kelas) {
            foreach ($kelas->siswa as $siswa) {
                foreach ($siswa->suratIzin as $izin) {
                    $notifikasi[] = [
                        'id_surat' => $izin->id_surat_izin,
                        'nama_siswa' => $siswa->nama,
                        'jenis' => $izin->jenis,
                        'tanggal_mulai' => Carbon::parse($izin->tanggal_mulai)->format('Y-m-d H:i:s'),
                        'tanggal_selesai' => Carbon::parse($izin->tanggal_selesai)->format('Y-m-d H:i:s'),
                        'alasan' => $izin->alasan,
                        'status' => $izin->status,
                        'dibuat_pada' => Carbon::parse($izin->dibuat_pada)->format('Y-m-d H:i:s'),
                        'diperbarui_pada' => $izin->updated_at ? Carbon::parse($izin->updated_at)->format('Y-m-d H:i:s') : null,
                        // Field file_lampiran yang dibutuhkan oleh Flutter
                        'file_lampiran' => $izin->file_lampiran ?? null,
                    ];
                }
            }
        }

        // Sort berdasarkan tanggal terbaru (opsional, karena Flutter juga melakukan sorting)
        usort($notifikasi, function ($a, $b) {
            return strtotime($b['dibuat_pada']) - strtotime($a['dibuat_pada']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi surat izin berhasil diambil.',
            'data' => [
                'data' => $notifikasi,
                'next_page_url' => null // Untuk kompatibilitas dengan pagination di Flutter
            ]
        ]);
    }
    public function jadwalMingguan($id)
    {
        try {
            // Ambil user berdasarkan ID
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.',
                    'data' => []
                ], 404);
            }

            // Ambil guru dari relasi user
            $guru = $user->guru;
            if (!$guru) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan.',
                    'data' => []
                ], 404);
            }

            $jadwal = Jadwal::where('id_guru', $guru->id_guru)
                ->with(['kelas.tahunAjaran', 'mataPelajaran']) // tambahkan relasi tahun ajaran
                ->orderBy('hari')
                ->orderBy('waktu_mulai')
                ->get();

            $formattedJadwal = $jadwal->map(function ($item) {
                $status = $this->getStatusJadwal($item->waktu_mulai, $item->waktu_selesai);

                return [
                    'id_jadwal' => $item->id_jadwal,
                    'hari' => $item->hari,
                    'kelas' => $item->kelas->nama_kelas ?? '-',
                    'id_kelas' => $item->kelas->id_kelas ?? '-',
                    'mata_pelajaran' => $item->mataPelajaran->nama ?? '-',
                    'waktu' => $item->waktu_mulai->format('H:i') . ' - ' . $item->waktu_selesai->format('H:i'),
                    'status' => $status,
                    'color' => $this->getStatusColor($status),
                    'tahun_ajaran' => $item->kelas->tahunAjaran->nama_tahun_ajaran ?? '-', // tambahan ini
                ];
            });


            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diambil.',
                'data' => $formattedJadwal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    // Get siswa berdasarkan kelas_id dari /guru/kelas/{id}/siswa
    public function getSiswaKelas($id)
    {
        $kelas = Kelas::with('siswa')->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data siswa',
            'data' => $kelas->siswa
        ]);
    }

    // Endpoint fallback: /guru/siswa?kelas_id={id}
    public function getSiswaByKelasId(Request $request)
    {
        $kelasId = $request->query('kelas_id');
        if (!$kelasId) {
            return response()->json([
                'success' => false,
                'message' => 'kelas_id harus diisi',
                'data' => []
            ], 400);
        }

        $siswa = Siswa::where('kelas_id', $kelasId)->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data siswa',
            'data' => $siswa
        ]);
    }

    // Optional: Get detail kelas + siswa langsung di /guru/kelas/{id}
    public function getDetailKelas($id)
    {
        $kelas = Kelas::with('siswa')->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil detail kelas',
            'data' => [
                'id' => $kelas->id_kelas,
                'nama' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat,
                'siswa' => $kelas->siswa
            ]
        ]);
    }

    public function updateProfile(Request $request, $id_user)
    {
        try {
            $guru = Guru::where('id_user', $id_user)->firstOrFail();

            // Validasi
            $validated = $request->validate([
                'nama_lengkap'   => 'required|string|max:255',
                'telepon'  => 'nullable|string|max:20',
                'password'       => 'nullable|string|min:6',
                'foto'           => 'nullable|image|max:2048',
            ]);

            // Siapkan data guru
            $guruData = [
                'nama_lengkap'    => $validated['nama_lengkap'],
                'nomor_telepon'   => $validated['telepon'] ?? null,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => $validated['nama_lengkap'],
            ];

            // Siapkan data user (hanya password jika dikirim)
            $userData = [];
            if (!empty($validated['password'])) {
                $userData['password'] = bcrypt($validated['password']); // disarankan dienkripsi
                $userData['diperbarui_oleh'] =  $validated['nama_lengkap'];
            }

            // Handle upload foto
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/foto_guru', $filename);
                $guruData['foto'] = $filename;
            }

            // Jalankan update
            UserService::updateGuruWithUser($guru->id_guru, $guruData, $userData);

            return response()->json([
                'success' => true,
                'message' => 'Profil guru berhasil diperbarui',
                'data' => Guru::with('user')->where('id_user', $id_user)->first()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage(),
            ], 500);
        }
    }
    // public function updateStatusSurat(Request $request, $idSurat, $status)
    // {
    //     // Validasi status
    //     if (!in_array($status, ['disetujui', 'ditolak', 'menunggu'])) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Status tidak valid.',
    //         ], 422);
    //     }

    //     // Ambil guru dari user login
    //     $user = auth()->user(); // gunakan sanctum
    //     if (!$user || !$user->guru || !$user->guru->kelas) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data guru tidak valid.',
    //         ], 404);
    //     }

    //     $kelas = $user->guru->kelas;
    //     $siswaIds = $kelas->siswa->pluck('id_siswa')->toArray();

    //     // Cari surat
    //     $surat = SuratIzin::find($idSurat);
    //     if (!$surat) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Surat tidak ditemukan.',
    //         ], 404);
    //     }

    //     if (!in_array($surat->id_siswa, $siswaIds)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Surat tidak termasuk dalam pengawasan guru ini.',
    //         ], 403);
    //     }

    //     // Simpan status dan catatan (opsional)
    //     $surat->status = $status;
    //     if ($request->filled('catatan')) {
    //         $surat->catatan = $request->catatan;
    //     }
    //     $surat->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Status surat berhasil diperbarui.',
    //         'data' => [
    //             'id_surat_izin' => $surat->id_surat_izin,
    //             'status' => $surat->status,
    //         ],
    //     ]);
    // }

    public function getSiswaByJadwal($id_jadwal)
    {
        try {
            // Validasi jadwal ada
            $jadwal = Jadwal::with('kelas')->findOrFail($id_jadwal);

            // Ambil data siswa berdasarkan kelas dari jadwal
            $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)
                ->with([
                    'absensi' => function ($query) use ($id_jadwal) {
                        $query->where('id_jadwal', $id_jadwal)
                            ->whereDate('tanggal', now());
                    },
                    'orangTua' // tambah relasi orang tua
                ])
                ->get();


            $data = $siswa->map(function ($item) use ($id_jadwal) {
                $absen = $item->absensi->first();
                return [
                    'id' => $item->id_siswa,
                    'nama' => $item->nama,
                    'nis' => $item->nis,
                    'status_kehadiran' => $absen ? $absen->status : null,
                    'orang_tua' => $item->orangTua ? [
                        'id' => $item->orangTua->id_orangtua,
                        'nama_lengkap' => $item->orangTua->nama_lengkap,
                        'alamat' => $item->orangTua->alamat,
                        'nomor_telepon' => $item->orangTua->nomor_telepon,
                        'pekerjaan' => $item->orangTua->pekerjaan,
                    ] : null,
                ];
            });


            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data siswa: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getRiwayatKehadiran($id_kelas)
    {
        try {
            // Ambil absensi berdasarkan kelas dan rentang tanggal
            $absensi = Absensi::whereHas('jadwal.kelas', function ($query) use ($id_kelas) {
                $query->where('id_kelas', $id_kelas);
            })
                ->whereBetween('tanggal', [request()->tanggal_mulai, request()->tanggal_selesai])
                ->get();

            // Proses data absensi
            $riwayatKehadiran = $absensi->map(function ($absen) {
                $jadwal = $absen->jadwal;
                $mataPelajaran = MataPelajaran::find($jadwal->id_mata_pelajaran);
                $kelas = $jadwal->kelas;

                // Format waktu
                $jamMulai = $jadwal->waktu_mulai->format('H:i');
                $jamSelesai = $jadwal->waktu_selesai->format('H:i');

                return [
                    'id_kehadiran' => (string) $absen->id_absensi,
                    'id_siswa' => (string) $absen->id_siswa, // Menambahkan id_siswa
                    'tanggal' => $absen->tanggal->format('Y-m-d'),
                    'status' => $absen->status,
                    'nama_kelas' => $kelas ? $kelas->nama_kelas : 'Tidak ditemukan',
                    'mata_pelajaran' => $mataPelajaran ? $mataPelajaran->nama : 'Tidak ditemukan',
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'catatan' => $absen->catatan,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $riwayatKehadiran,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function getDetailSiswa($id)
    {
        try {
            $siswa = Siswa::select('id_siswa as id', 'nama as nama', 'nis', 'jenis_kelamin')
                ->where('id_siswa', $id)
                ->first();

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan',
                ], 404);
            }

            // Normalisasi jenis kelamin
            $jenisKelamin = strtolower($siswa->jenis_kelamin);
            $jenisKelamin = $jenisKelamin === 'laki-laki' || $jenisKelamin === 'laki_laki' ? 'L' : 'P';

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => (string) $siswa->id,
                    'nama' => $siswa->nama,
                    'nis' => $siswa->nis,
                    'jenis_kelamin' => $jenisKelamin,
                    'no_telp' => $siswa->no_telp,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getRekapitulasi()
    {
        try {
            // Ambil semua jadwal dengan relasi kelas, tahun ajaran, dan siswa
            $jadwals = \App\Models\Jadwal::with(['kelas.tahunAjaran', 'kelas.siswa'])->get();

            // Buat rekap data
            $rekap = $jadwals->map(function ($item) {
                return [
                    'id_kelas' => (int) $item->id_kelas,
                    'kelas' => $item->kelas->nama_kelas ?? 'Tidak diketahui',
                    'tahun_ajaran' => $item->kelas->tahunAjaran->nama_tahun_ajaran ?? 'Belum ditentukan',
                    'jumlah_siswa' => $item->kelas->siswa->count() ?? 0,
                ];
            })->unique(function ($item) {
                return $item['id_kelas'] . '_' . $item['tahun_ajaran'];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $rekap
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetailRekapitulasi(Request $request)
    {
        $idKelas = $request->query('id_kelas');
        $namaTahunAjaran = $request->query('tahun_ajaran');

        try {
            // Validasi
            if (!$idKelas || !$namaTahunAjaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter id_kelas dan tahun_ajaran dibutuhkan.',
                ], 400);
            }

            // Ambil ID Tahun Ajaran
            $tahunAjaran = \App\Models\TahunAjaran::where('nama_tahun_ajaran', $namaTahunAjaran)->first();
            if (!$tahunAjaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun ajaran tidak ditemukan.',
                ], 404);
            }

            // Ambil semua siswa dalam kelas dan tahun ajaran tersebut
            $siswaCount = \App\Models\Siswa::where('id_kelas', $idKelas)
                ->where('id_tahun_ajaran', $tahunAjaran->id_tahun_ajaran)
                ->count();

            if ($siswaCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada siswa ditemukan dalam kelas ini.',
                ], 404);
            }

            // Ambil semua jadwal yang sesuai kelas dan tahun ajaran
            $jadwalList = \App\Models\Jadwal::with('mataPelajaran') // pastikan relasi ini ada
                ->where('id_kelas', $idKelas)
                ->where('id_tahun_ajaran', $tahunAjaran->id_tahun_ajaran)
                ->get();

            if ($jadwalList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal ditemukan.',
                ], 404);
            }

            // Ambil semua absensi terkait jadwal yang ditemukan
            $jadwalIds = $jadwalList->pluck('id_jadwal');

            $absensiList = \App\Models\Absensi::whereIn('id_jadwal', $jadwalIds)
                ->orderBy('tanggal', 'asc')
                ->get();

            if ($absensiList->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [], // tetap success tapi kosong
                    'message' => 'Belum ada data absensi tersedia.',
                ]);
            }

            // Kelompokkan absensi berdasarkan tanggal dan jadwal
            $rekap = $absensiList->groupBy(function ($item) {
                return $item->tanggal->toDateString() . '-' . $item->id_jadwal;
            })->map(function ($items) use ($siswaCount, $jadwalList) {
                $first = $items->first();
                $jadwal = $jadwalList->firstWhere('id_jadwal', $first->id_jadwal);

                $hadirCount = $items->where('status', 'hadir')->count();
                $persenKehadiran = round(($hadirCount / $siswaCount) * 100, 2);

                // Tambahkan data siswa yang tidak hadir
                $siswaAbsen = [];
                foreach ($items as $absensi) {
                    if ($absensi->status !== 'hadir') {
                        $siswa = $absensi->siswa;
                        if ($siswa) {
                            $siswaAbsen[] = [
                                'id' => $siswa->id_siswa,
                                'nama' => $siswa->nama,
                                'nis' => $siswa->nis,
                                'status' => $absensi->status,
                                'keterangan' => $absensi->catatan
                            ];
                        }
                    }
                }

                return [
                    'tanggal' => $first->tanggal->toDateString(),
                    'topik' => optional($jadwal->mataPelajaran)->nama ?? 'Tidak diketahui',
                    'kehadiran' => $persenKehadiran,
                    'catatan' => $first->catatan ?? null,
                    'siswa_tidak_hadir' => $siswaAbsen
                ];
            })->values(); // ubah ke array numerik

            return response()->json([
                'success' => true,
                'data' => $rekap,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
