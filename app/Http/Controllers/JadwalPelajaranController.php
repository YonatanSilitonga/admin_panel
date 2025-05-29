<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalPelajaranController extends Controller
{
    /**
     * Konstanta untuk waktu sesi pelajaran
     */
    const SESI_WAKTU = [
        1 => ['waktu_mulai' => '07:45:00', 'waktu_selesai' => '08:30:00', 'label' => 'Sesi 1 (07:45 - 08:30)'],
        2 => ['waktu_mulai' => '08:30:00', 'waktu_selesai' => '09:15:00', 'label' => 'Sesi 2 (08:30 - 09:15)'],
        3 => ['waktu_mulai' => '09:15:00', 'waktu_selesai' => '10:00:00', 'label' => 'Sesi 3 (09:15 - 10:00)'],
        4 => ['waktu_mulai' => '10:15:00', 'waktu_selesai' => '11:00:00', 'label' => 'Sesi 4 (10:15 - 11:00)'],
        5 => ['waktu_mulai' => '11:00:00', 'waktu_selesai' => '11:45:00', 'label' => 'Sesi 5 (11:00 - 11:45)'],
        6 => ['waktu_mulai' => '11:45:00', 'waktu_selesai' => '12:30:00', 'label' => 'Sesi 6 (11:45 - 12:30)'],
    ];

    const HARI_LIST = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter berdasarkan kelas, hari, dan tahun ajaran
        $kelasId = $request->input('kelas');
        $hari = $request->input('hari');
        $tahunAjaranId = $request->input('tahun_ajaran');

        // Ambil data untuk filter dropdown
        $kelasList = Kelas::with('tahunAjaran')->orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
        $tahunAjaranList = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();

        // Query jadwal dengan filter
        $query = Jadwal::with(['kelas.tahunAjaran', 'mataPelajaran', 'guru', 'tahunAjaran']);

        if ($kelasId) {
            $query->where('id_kelas', $kelasId);
        }

        if ($hari) {
            $query->where('hari', $hari);
        }

        if ($tahunAjaranId) {
            $query->where('id_tahun_ajaran', $tahunAjaranId);
        } else {
            // Default to active academic year if no specific year is selected
            if ($tahunAjaranAktif) {
                $query->where('id_tahun_ajaran', $tahunAjaranAktif->id_tahun_ajaran);
                $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran;
            }
        }

        $query->where('status', 'aktif');

        // Urutkan jadwal
        $jadwalList = $query->orderBy('hari')
            ->orderBy('waktu_mulai')
            ->orderBy('id_kelas')
            ->get();

        // Kelompokkan jadwal berdasarkan hari dan kelas untuk tampilan grid
        $jadwalByHariKelas = [];
        foreach (self::HARI_LIST as $h) {
            $jadwalByHariKelas[$h] = [];
            foreach ($kelasList as $k) {
                $jadwalByHariKelas[$h][$k->id_kelas] = $jadwalList->filter(function ($jadwal) use ($h, $k) {
                    return $jadwal->hari === $h && $jadwal->id_kelas === $k->id_kelas;
                })->sortBy('waktu_mulai')->values();
            }
        }

        return view('admin.pages.jadwal_pelajaran.index', compact(
            'jadwalList',
            'jadwalByHariKelas',
            'kelasList',
            'mataPelajaranList',
            'tahunAjaranList',
            'tahunAjaranAktif',
            'kelasId',
            'hari',
            'tahunAjaranId'
        ));
    }

    /**
     * Store jadwal massal untuk satu kelas dengan support untuk edit existing.
     */
    public function storeMassal(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'jadwal' => 'required|array',
            'jadwal.*.*' => 'array',
            'jadwal.*.*.id_mata_pelajaran' => 'nullable|exists:mata_pelajaran,id_mata_pelajaran',
            'jadwal.*.*.id_guru' => 'nullable|exists:guru,id_guru',
            'jadwal.*.*.id_jadwal' => 'nullable|exists:jadwal,id_jadwal',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil tahun ajaran dari kelas
        $kelas = Kelas::find($request->id_kelas);
        $tahunAjaranId = $kelas->id_tahun_ajaran;

        if (!$tahunAjaranId) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas belum memiliki tahun ajaran.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $jadwalData = $request->jadwal;
            $conflicts = [];
            $createdCount = 0;
            $updatedCount = 0;
            $deletedCount = 0;

            // Get all existing jadwal for this class
            $existingJadwals = Jadwal::where('id_kelas', $request->id_kelas)
                ->where('id_tahun_ajaran', $tahunAjaranId)
                ->where('status', 'aktif')
                ->get()
                ->keyBy('id_jadwal');

            $processedJadwalIds = [];

            // Process each jadwal entry
            foreach ($jadwalData as $hari => $sesiData) {
                foreach ($sesiData as $sesi => $data) {
                    $sesiInfo = self::SESI_WAKTU[$sesi];
                    $existingJadwalId = !empty($data['id_jadwal']) ? $data['id_jadwal'] : null;

                    // If both mata pelajaran and guru are empty, delete existing if any
                    if (empty($data['id_mata_pelajaran']) && empty($data['id_guru'])) {
                        if ($existingJadwalId && isset($existingJadwals[$existingJadwalId])) {
                            $existingJadwals[$existingJadwalId]->delete();
                            $deletedCount++;
                        }
                        continue;
                    }

                    // If only one field is filled, it's an error
                    if (empty($data['id_mata_pelajaran']) || empty($data['id_guru'])) {
                        $conflicts[] = "Data tidak lengkap pada hari " . ucfirst($hari) . " sesi " . $sesi;
                        continue;
                    }

                    // Validasi guru mengampu mata pelajaran
                    $guruMataPelajaran = DB::table('guru_mata_pelajaran')
                        ->where('id_guru', $data['id_guru'])
                        ->where('id_mata_pelajaran', $data['id_mata_pelajaran'])
                        ->exists();

                    if (!$guruMataPelajaran) {
                        $conflicts[] = "Guru tidak mengampu mata pelajaran pada hari " . ucfirst($hari) . " sesi " . $sesi;
                        continue;
                    }

                    // Prepare exclude IDs for conflict check
                    $excludeIds = [];
                    if ($existingJadwalId) {
                        $excludeIds = [$existingJadwalId];
                    }

                    // Check conflicts
                    $sessionConflicts = $this->checkSessionConflicts(
                        $request->id_kelas, 
                        $data['id_guru'], 
                        $hari, 
                        [$sesi], 
                        $tahunAjaranId,
                        $excludeIds
                    );

                    if (!empty($sessionConflicts)) {
                        foreach ($sessionConflicts as $conflict) {
                            $conflictDetail = "";
                            if ($conflict['type'] === 'teacher') {
                                $conflictDetail = "Guru sudah mengajar di kelas lain pada waktu yang sama";
                            } elseif ($conflict['type'] === 'class') {
                                $conflictDetail = "Kelas sudah memiliki jadwal lain pada waktu yang sama";
                            }
                            $conflicts[] = "Konflik pada hari " . ucfirst($hari) . " sesi " . $sesi . ": " . $conflictDetail;
                        }
                        continue;
                    }

                    // Create or update jadwal
                    if ($existingJadwalId && isset($existingJadwals[$existingJadwalId])) {
                        // Update existing jadwal
                        $jadwal = $existingJadwals[$existingJadwalId];
                        $jadwal->id_mata_pelajaran = $data['id_mata_pelajaran'];
                        $jadwal->id_guru = $data['id_guru'];
                        $jadwal->hari = $hari;
                        $jadwal->waktu_mulai = $sesiInfo['waktu_mulai'];
                        $jadwal->waktu_selesai = $sesiInfo['waktu_selesai'];
                        $jadwal->diperbarui_pada = now();
                        $jadwal->diperbarui_oleh = Auth::user()->username ?? 'system';
                        $jadwal->save();
                        $updatedCount++;
                        $processedJadwalIds[] = $existingJadwalId;
                    } else {
                        // Create new jadwal
                        $jadwal = new Jadwal();
                        $jadwal->id_kelas = $request->id_kelas;
                        $jadwal->id_mata_pelajaran = $data['id_mata_pelajaran'];
                        $jadwal->id_guru = $data['id_guru'];
                        $jadwal->id_tahun_ajaran = $tahunAjaranId;
                        $jadwal->hari = $hari;
                        $jadwal->waktu_mulai = $sesiInfo['waktu_mulai'];
                        $jadwal->waktu_selesai = $sesiInfo['waktu_selesai'];
                        $jadwal->status = 'aktif';
                        $jadwal->dibuat_pada = now();
                        $jadwal->dibuat_oleh = Auth::user()->username ?? 'system';
                        $jadwal->save();
                        $createdCount++;
                        $processedJadwalIds[] = $jadwal->id_jadwal;
                    }
                }
            }

            // If there are conflicts, return error
            if (!empty($conflicts)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat konflik jadwal',
                    'conflicts' => $conflicts
                ], 422);
            }

            DB::commit();

            $message = "Jadwal berhasil diperbarui. ";
            if ($createdCount > 0) $message .= "Dibuat: {$createdCount}. ";
            if ($updatedCount > 0) $message .= "Diperbarui: {$updatedCount}. ";
            if ($deletedCount > 0) $message .= "Dihapus: {$deletedCount}.";

            return response()->json([
                'success' => true,
                'message' => $message,
                'stats' => [
                    'created' => $createdCount,
                    'updated' => $updatedCount,
                    'deleted' => $deletedCount
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jadwal massal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get existing jadwal for a class to populate the mass schedule table.
     */
    public function getJadwalByKelas($kelasId)
    {
        try {
            $kelas = Kelas::find($kelasId);
            if (!$kelas || !$kelas->id_tahun_ajaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan atau belum memiliki tahun ajaran.'
                ]);
            }

            $jadwalList = Jadwal::where('id_kelas', $kelasId)
                ->where('id_tahun_ajaran', $kelas->id_tahun_ajaran)
                ->where('status', 'aktif')
                ->with(['mataPelajaran', 'guru'])
                ->get();

            // Organize jadwal by day and session
            $jadwalByHariSesi = [];
            foreach ($jadwalList as $jadwal) {
                // Find session number based on time
                foreach (self::SESI_WAKTU as $sesiNum => $sesiData) {
                    if ($jadwal->waktu_mulai == $sesiData['waktu_mulai']) {
                        $jadwalByHariSesi[$jadwal->hari][$sesiNum] = [
                            'id_jadwal' => $jadwal->id_jadwal,
                            'id_mata_pelajaran' => $jadwal->id_mata_pelajaran,
                            'id_guru' => $jadwal->id_guru,
                            'mata_pelajaran_nama' => $jadwal->mataPelajaran->nama,
                            'guru_nama' => $jadwal->guru->nama_lengkap
                        ];
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $jadwalByHariSesi,
                'kelas_info' => [
                    'nama' => $kelas->tingkat . ' ' . $kelas->nama_kelas,
                    'tahun_ajaran' => $kelas->tahunAjaran->nama_tahun_ajaran ?? 'Tidak ada tahun ajaran'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'guru', 'tahunAjaran'])->findOrFail($id);
            
            // Determine session number based on time
            $sesiNumber = null;
            foreach (self::SESI_WAKTU as $sesiNum => $sesiData) {
                if ($jadwal->waktu_mulai == $sesiData['waktu_mulai']) {
                    $sesiNumber = $sesiNum;
                    break;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $jadwal,
                'sesi_number' => $sesiNumber
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get guru by mata pelajaran.
     */
    public function getGuruByMataPelajaran($idMataPelajaran)
    {
        try {
            $guruList = Guru::whereHas('mataPelajaran', function ($query) use ($idMataPelajaran) {
                $query->where('mata_pelajaran.id_mata_pelajaran', $idMataPelajaran);
            })
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get(['id_guru', 'nama_lengkap']);

            return response()->json([
                'success' => true,
                'data' => $guruList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data guru: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for schedule conflicts.
     */
    private function checkSessionConflicts($kelasId, $guruId, $hari, $sessions, $tahunAjaranId, $excludeIds = [])
    {
        $conflicts = [];

        foreach ($sessions as $sesi) {
            $sesiData = self::SESI_WAKTU[$sesi];
            $waktuMulai = $sesiData['waktu_mulai'];
            $waktuSelesai = $sesiData['waktu_selesai'];

            // Check class conflicts
            $classConflict = Jadwal::where('id_kelas', $kelasId)
                ->where('hari', $hari)
                ->where('id_tahun_ajaran', $tahunAjaranId)
                ->where('status', 'aktif')
                ->where(function($query) use ($waktuMulai, $waktuSelesai) {
                    $query->where(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->where('waktu_mulai', '>=', $waktuMulai)
                          ->where('waktu_mulai', '<', $waktuSelesai);
                    })->orWhere(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->where('waktu_selesai', '>', $waktuMulai)
                          ->where('waktu_selesai', '<=', $waktuSelesai);
                    })->orWhere(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->where('waktu_mulai', '<=', $waktuMulai)
                          ->where('waktu_selesai', '>=', $waktuSelesai);
                    });
                });

            if (!empty($excludeIds)) {
                $classConflict->whereNotIn('id_jadwal', $excludeIds);
            }

            $classConflictResult = $classConflict->with(['mataPelajaran', 'guru'])->first();

            if ($classConflictResult) {
                $conflicts[] = [
                    'type' => 'class',
                    'sesi' => $sesi,
                    'message' => "Kelas sudah memiliki jadwal {$classConflictResult->mataPelajaran->nama} dengan {$classConflictResult->guru->nama_lengkap} pada sesi {$sesi}"
                ];
            }

            // Check teacher conflicts
            $teacherConflict = Jadwal::where('id_guru', $guruId)
                ->where('hari', $hari)
                ->where('id_tahun_ajaran', $tahunAjaranId)
                ->where('status', 'aktif')
                ->where(function($query) use ($waktuMulai, $waktuSelesai) {
                    $query->where(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->where('waktu_mulai', '>=', $waktuMulai)
                          ->where('waktu_mulai', '<', $waktuSelesai);
                    })->orWhere(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->where('waktu_selesai', '>', $waktuMulai)
                          ->where('waktu_selesai', '<=', $waktuSelesai);
                    })->orWhere(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->where('waktu_mulai', '<=', $waktuMulai)
                          ->where('waktu_selesai', '>=', $waktuSelesai);
                    });
                });

            if (!empty($excludeIds)) {
                $teacherConflict->whereNotIn('id_jadwal', $excludeIds);
            }

            $teacherConflictResult = $teacherConflict->with(['kelas', 'mataPelajaran'])->first();

            if ($teacherConflictResult) {
                $conflicts[] = [
                    'type' => 'teacher',
                    'sesi' => $sesi,
                    'message' => "Guru sudah mengajar {$teacherConflictResult->mataPelajaran->nama} di kelas {$teacherConflictResult->kelas->nama_kelas} pada sesi {$sesi}"
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelasList = Kelas::with('tahunAjaran')->orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
        $guruList = Guru::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $tahunAjaranList = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();
        $sesiList = self::SESI_WAKTU;

        return view('admin.pages.jadwal_pelajaran.create', compact(
            'kelasList',
            'mataPelajaranList',
            'guruList',
            'tahunAjaranList',
            'sesiList'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'id_guru' => 'required|exists:guru,id_guru',
            'sesi' => 'required|array|min:1',
            'sesi.*' => 'integer|min:1|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validasi guru mengampu mata pelajaran
        $guruMataPelajaran = DB::table('guru_mata_pelajaran')
            ->where('id_guru', $request->id_guru)
            ->where('id_mata_pelajaran', $request->id_mata_pelajaran)
            ->exists();

        if (!$guruMataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Guru yang dipilih tidak mengampu mata pelajaran tersebut.'
            ], 422);
        }

        // Ambil tahun ajaran dari kelas
        $kelas = Kelas::find($request->id_kelas);
        $tahunAjaranId = $kelas->id_tahun_ajaran;

        if (!$tahunAjaranId) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas belum memiliki tahun ajaran.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $selectedSessions = $request->sesi;
            sort($selectedSessions);

            // Cek konflik untuk semua sesi
            $conflicts = $this->checkSessionConflicts($request->id_kelas, $request->id_guru, $request->hari, $selectedSessions, $tahunAjaranId);
            
            if (!empty($conflicts)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat konflik jadwal',
                    'conflicts' => $conflicts
                ], 422);
            }

            // Buat jadwal untuk setiap sesi
            foreach ($selectedSessions as $sesi) {
                $sesiData = self::SESI_WAKTU[$sesi];
                
                $jadwal = new Jadwal();
                $jadwal->id_kelas = $request->id_kelas;
                $jadwal->id_mata_pelajaran = $request->id_mata_pelajaran;
                $jadwal->id_guru = $request->id_guru;
                $jadwal->id_tahun_ajaran = $tahunAjaranId;
                $jadwal->hari = $request->hari;
                $jadwal->waktu_mulai = $sesiData['waktu_mulai'];
                $jadwal->waktu_selesai = $sesiData['waktu_selesai'];
                $jadwal->status = 'aktif';
                $jadwal->dibuat_pada = now();
                $jadwal->dibuat_oleh = Auth::user()->username ?? 'system';
                $jadwal->save();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'guru', 'tahunAjaran'])->findOrFail($id);
        $kelasList = Kelas::with('tahunAjaran')->orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
        $guruList = Guru::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $tahunAjaranList = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();
        $sesiList = self::SESI_WAKTU;

        // Determine session number
        $selectedSessions = [];
        foreach (self::SESI_WAKTU as $sesiNum => $sesiData) {
            if ($jadwal->waktu_mulai == $sesiData['waktu_mulai']) {
                $selectedSessions[] = $sesiNum;
                break;
            }
        }

        return view('admin.pages.jadwal_pelajaran.edit', compact(
            'jadwal',
            'kelasList',
            'mataPelajaranList',
            'guruList',
            'tahunAjaranList',
            'sesiList',
            'selectedSessions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'id_guru' => 'required|exists:guru,id_guru',
            'sesi' => 'required|integer|min:1|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validasi guru mengampu mata pelajaran
        $guruMataPelajaran = DB::table('guru_mata_pelajaran')
            ->where('id_guru', $request->id_guru)
            ->where('id_mata_pelajaran', $request->id_mata_pelajaran)
            ->exists();

        if (!$guruMataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Guru yang dipilih tidak mengampu mata pelajaran tersebut.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $jadwal = Jadwal::findOrFail($id);
            $sesiData = self::SESI_WAKTU[$request->sesi];

            // Check conflicts excluding current jadwal
            $conflicts = $this->checkSessionConflicts(
                $request->id_kelas, 
                $request->id_guru, 
                $request->hari, 
                [$request->sesi], 
                $jadwal->id_tahun_ajaran,
                [$id]
            );
            
            if (!empty($conflicts)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat konflik jadwal',
                    'conflicts' => $conflicts
                ], 422);
            }

            // Update jadwal
            $jadwal->id_kelas = $request->id_kelas;
            $jadwal->id_mata_pelajaran = $request->id_mata_pelajaran;
            $jadwal->id_guru = $request->id_guru;
            $jadwal->hari = $request->hari;
            $jadwal->waktu_mulai = $sesiData['waktu_mulai'];
            $jadwal->waktu_selesai = $sesiData['waktu_selesai'];
            $jadwal->diperbarui_pada = now();
            $jadwal->diperbarui_oleh = Auth::user()->username ?? 'system';
            $jadwal->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}
