<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Konstanta sesi waktu
     */
    const SESI_WAKTU = [
        1 => ['waktu_mulai' => '07:45:00', 'waktu_selesai' => '08:30:00', 'label' => 'Sesi 1 (07:45 - 08:30)'],
        2 => ['waktu_mulai' => '08:30:00', 'waktu_selesai' => '09:15:00', 'label' => 'Sesi 2 (08:30 - 09:15)'],
        3 => ['waktu_mulai' => '09:15:00', 'waktu_selesai' => '10:00:00', 'label' => 'Sesi 3 (09:15 - 10:00)'],
        4 => ['waktu_mulai' => '10:15:00', 'waktu_selesai' => '11:00:00', 'label' => 'Sesi 4 (10:15 - 11:00)'],
        5 => ['waktu_mulai' => '11:00:00', 'waktu_selesai' => '11:45:00', 'label' => 'Sesi 5 (11:00 - 11:45)'],
        6 => ['waktu_mulai' => '11:45:00', 'waktu_selesai' => '12:30:00', 'label' => 'Sesi 6 (11:45 - 12:30)'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Validasi prerequisite
        if (!$this->validasiPrerequisite()) {
            return;
        }

        // Hapus data yang sudah ada dengan aman
        $this->hapusDataJadwalAman();

        $jadwal = [];
        $id_counter = 1;

        // Cek mata pelajaran yang tersedia
        $mata_pelajaran_tersedia = DB::table('mata_pelajaran')
            ->pluck('id_mata_pelajaran')
            ->toArray();

        if (empty($mata_pelajaran_tersedia)) {
            echo "⚠️  Tidak ada mata pelajaran. Jalankan MataPelajaranSeeder terlebih dahulu.\n";
            return;
        }

        // Mata pelajaran dengan jumlah jam per minggu (hanya yang tersedia)
        $mata_pelajaran_jam = [];
        foreach ($mata_pelajaran_tersedia as $id_mapel) {
            switch ($id_mapel) {
                case 1: $mata_pelajaran_jam[1] = 6; break; // Matematika
                case 2: $mata_pelajaran_jam[2] = 6; break; // Bahasa Indonesia
                case 3: $mata_pelajaran_jam[3] = 4; break; // Bahasa Inggris
                case 4: $mata_pelajaran_jam[4] = 6; break; // IPA
                case 5: $mata_pelajaran_jam[5] = 3; break; // PPKn
                case 6: $mata_pelajaran_jam[6] = 4; break; // IPS
                case 7: $mata_pelajaran_jam[7] = 3; break; // Seni Budaya
                case 8: $mata_pelajaran_jam[8] = 3; break; // PJOK
                case 9: $mata_pelajaran_jam[9] = 3; break; // Prakarya
                case 10: $mata_pelajaran_jam[10] = 2; break; // Pendidikan Agama
                case 11: $mata_pelajaran_jam[11] = 1; break; // Bimbingan Konseling
                case 12: $mata_pelajaran_jam[12] = 1; break; // TIK
            }
        }

        // Cek guru yang tersedia di database
        $guru_tersedia = DB::table('guru')->where('status', 'aktif')->get()->keyBy('id_guru');
        
        if ($guru_tersedia->isEmpty()) {
            echo "⚠️  Tidak ada data guru aktif. Jalankan GuruSeeder terlebih dahulu.\n";
            return;
        }

        // Guru berdasarkan mata pelajaran (gunakan yang ada di database)
        $guru_mapel = [];
        foreach ($guru_tersedia as $guru) {
            switch ($guru->bidang_studi) {
                case 'Matematika':
                    if (in_array(1, $mata_pelajaran_tersedia)) $guru_mapel[1][] = $guru->id_guru;
                    break;
                case 'Bahasa Indonesia':
                    if (in_array(2, $mata_pelajaran_tersedia)) $guru_mapel[2][] = $guru->id_guru;
                    break;
                case 'Bahasa Inggris':
                    if (in_array(3, $mata_pelajaran_tersedia)) $guru_mapel[3][] = $guru->id_guru;
                    break;
                case 'IPA':
                    if (in_array(4, $mata_pelajaran_tersedia)) $guru_mapel[4][] = $guru->id_guru;
                    break;
                case 'PPKn':
                    if (in_array(5, $mata_pelajaran_tersedia)) $guru_mapel[5][] = $guru->id_guru;
                    break;
                case 'IPS':
                    if (in_array(6, $mata_pelajaran_tersedia)) $guru_mapel[6][] = $guru->id_guru;
                    break;
                case 'Seni Budaya':
                    if (in_array(7, $mata_pelajaran_tersedia)) $guru_mapel[7][] = $guru->id_guru;
                    break;
                case 'PJOK':
                    if (in_array(8, $mata_pelajaran_tersedia)) $guru_mapel[8][] = $guru->id_guru;
                    break;
                case 'Prakarya':
                    if (in_array(9, $mata_pelajaran_tersedia)) $guru_mapel[9][] = $guru->id_guru;
                    break;
                case 'Pendidikan Agama':
                    if (in_array(10, $mata_pelajaran_tersedia)) $guru_mapel[10][] = $guru->id_guru;
                    break;
                case 'Bimbingan Konseling':
                    if (in_array(11, $mata_pelajaran_tersedia)) $guru_mapel[11][] = $guru->id_guru;
                    break;
                case 'TIK':
                    if (in_array(12, $mata_pelajaran_tersedia)) $guru_mapel[12][] = $guru->id_guru;
                    break;
            }
        }

        // Pastikan setiap mata pelajaran memiliki minimal 1 guru
        foreach ($mata_pelajaran_jam as $mapel_id => $jam) {
            if (!isset($guru_mapel[$mapel_id]) || empty($guru_mapel[$mapel_id])) {
                // Jika tidak ada guru untuk mata pelajaran ini, gunakan guru pertama sebagai fallback
                $guru_mapel[$mapel_id] = [$guru_tersedia->first()->id_guru];
                echo "⚠️  Mata pelajaran ID $mapel_id tidak memiliki guru khusus, menggunakan fallback.\n";
            }
        }

        // Kelas data - cek yang ada di database
        $kelas_tersedia = DB::table('kelas')->get()->keyBy('id_kelas');
        
        if ($kelas_tersedia->isEmpty()) {
            echo "⚠️  Tidak ada data kelas. Jalankan KelasSeeder terlebih dahulu.\n";
            return;
        }

        // Tambahkan hari sabtu
        $hari_list = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        // Tracking jadwal guru: [hari][sesi] = [id_guru1, id_guru2, ...]
        $jadwal_guru = [];
        foreach ($hari_list as $hari) {
            for ($sesi = 1; $sesi <= 6; $sesi++) {
                $jadwal_guru[$hari][$sesi] = [];
            }
        }

        // Generate jadwal untuk setiap kelas
        foreach ($kelas_tersedia as $id_kelas => $kelas_info) {
            // Buat jadwal mingguan untuk kelas ini
            $jadwal_mingguan = $this->buatJadwalMingguan($mata_pelajaran_jam);
            
            foreach ($hari_list as $hari_index => $hari) {
                for ($sesi = 1; $sesi <= 6; $sesi++) {
                    if (isset($jadwal_mingguan[$hari_index][$sesi - 1])) {
                        $id_mata_pelajaran = $jadwal_mingguan[$hari_index][$sesi - 1];
                        
                        // Validasi mata pelajaran ada di database
                        if (!in_array($id_mata_pelajaran, $mata_pelajaran_tersedia)) {
                            echo "⚠️  Mata pelajaran ID $id_mata_pelajaran tidak ditemukan, skip.\n";
                            continue;
                        }
                        
                        // Cari guru yang tersedia untuk mata pelajaran ini
                        $guru_tersedia_mapel = $guru_mapel[$id_mata_pelajaran] ?? [];
                        $id_guru = $this->cariGuruTersedia($guru_tersedia_mapel, $jadwal_guru, $hari, $sesi);
                        
                        if ($id_guru) {
                            // Tambahkan guru ke tracking
                            $jadwal_guru[$hari][$sesi][] = $id_guru;
                            
                            $jadwal[] = [
                                'id_jadwal' => $id_counter++,
                                'id_kelas' => $id_kelas,
                                'id_mata_pelajaran' => $id_mata_pelajaran,
                                'id_guru' => $id_guru,
                                'id_tahun_ajaran' => 1,
                                'hari' => $hari,
                                'waktu_mulai' => self::SESI_WAKTU[$sesi]['waktu_mulai'],
                                'waktu_selesai' => self::SESI_WAKTU[$sesi]['waktu_selesai'],
                                'status' => 'aktif',
                                'dibuat_pada' => Carbon::now(),
                                'dibuat_oleh' => 'system',
                                'diperbarui_pada' => Carbon::now(),
                                'diperbarui_oleh' => 'system'
                            ];
                        }
                    }
                }
            }
        }

        // Insert ke database dalam batch
        if (!empty($jadwal)) {
            $chunks = array_chunk($jadwal, 100);
            foreach ($chunks as $chunk) {
                DB::table('jadwal')->insert($chunk);
            }
            echo "✅ Berhasil membuat " . count($jadwal) . " jadwal.\n";
        } else {
            echo "⚠️  Tidak ada jadwal yang dibuat.\n";
        }

        $this->tampilkanStatistikJadwal($jadwal_guru);
        $this->tampilkanDistribusiGuru($guru_mapel);
        $this->tampilkanRingkasanJadwal($kelas_tersedia->count(), count($hari_list));
    }

    /**
     * Validasi prerequisite sebelum membuat jadwal
     */
    private function validasiPrerequisite()
    {
        $errors = [];

        // Cek tabel mata_pelajaran
        if (DB::table('mata_pelajaran')->count() == 0) {
            $errors[] = "Tabel mata_pelajaran kosong. Jalankan MataPelajaranSeeder terlebih dahulu.";
        }

        // Cek tabel guru
        if (DB::table('guru')->count() == 0) {
            $errors[] = "Tabel guru kosong. Jalankan GuruSeeder terlebih dahulu.";
        }

        // Cek tabel kelas
        if (DB::table('kelas')->count() == 0) {
            $errors[] = "Tabel kelas kosong. Jalankan KelasSeeder terlebih dahulu.";
        }

        // Cek tabel users
        if (DB::table('users')->count() == 0) {
            $errors[] = "Tabel users kosong. Jalankan UserSeeder terlebih dahulu.";
        }

        if (!empty($errors)) {
            echo "❌ PREREQUISITE ERROR:\n";
            foreach ($errors as $error) {
                echo "   - $error\n";
            }
            echo "\n💡 Jalankan: php artisan db:seed\n";
            return false;
        }

        return true;
    }

    /**
     * Hapus data jadwal dengan aman
     */
    private function hapusDataJadwalAman()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('absensi')->delete();
            echo "🗑️  Data absensi dihapus.\n";
            DB::table('jadwal')->delete();
            echo "🗑️  Data jadwal dihapus.\n";
            DB::statement('ALTER TABLE jadwal AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE absensi AUTO_INCREMENT = 1;');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            echo "⚠️  Error saat menghapus data: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Buat jadwal mingguan berdasarkan alokasi jam (6 hari)
     */
    private function buatJadwalMingguan($mata_pelajaran_jam)
    {
        $jadwal_mingguan = [[], [], [], [], [], []]; // 6 hari
        
        // Buat array mata pelajaran berdasarkan jam yang dialokasikan
        $mata_pelajaran_list = [];
        foreach ($mata_pelajaran_jam as $mapel => $jam) {
            for ($i = 0; $i < $jam; $i++) {
                $mata_pelajaran_list[] = $mapel;
            }
        }
        
        // Shuffle untuk variasi
        shuffle($mata_pelajaran_list);
        
        // Distribusikan ke hari dan sesi
        $index = 0;
        for ($hari = 0; $hari < 6; $hari++) {
            for ($sesi = 0; $sesi < 6; $sesi++) {
                if ($index < count($mata_pelajaran_list)) {
                    $jadwal_mingguan[$hari][$sesi] = $mata_pelajaran_list[$index];
                    $index++;
                }
            }
        }
        
        return $jadwal_mingguan;
    }

    /**
     * Cari guru yang tersedia untuk sesi tertentu
     */
    private function cariGuruTersedia($guru_list, $jadwal_guru, $hari, $sesi)
    {
        if (empty($guru_list)) {
            return null;
        }

        foreach ($guru_list as $id_guru) {
            if (!in_array($id_guru, $jadwal_guru[$hari][$sesi])) {
                return $id_guru;
            }
        }
        
        // Jika semua guru sudah terpakai, gunakan guru dengan beban paling sedikit
        $beban_guru = [];
        foreach ($guru_list as $id_guru) {
            $beban_guru[$id_guru] = 0;
            foreach ($jadwal_guru as $h => $sesi_list) {
                foreach ($sesi_list as $s => $guru_sesi) {
                    $beban_guru[$id_guru] += count(array_filter($guru_sesi, function($g) use ($id_guru) {
                        return $g == $id_guru;
                    }));
                }
            }
        }
        
        asort($beban_guru);
        return array_key_first($beban_guru);
    }

    /**
     * Tampilkan statistik jadwal guru
     */
    private function tampilkanStatistikJadwal($jadwal_guru)
    {
        echo "\n=== STATISTIK JADWAL GURU ===\n";
        
        foreach ($jadwal_guru as $hari => $sesi_list) {
            echo "\n" . strtoupper($hari) . ":\n";
            foreach ($sesi_list as $sesi => $guru_list) {
                $waktu = self::SESI_WAKTU[$sesi]['label'];
                $jumlah_guru = count($guru_list);
                echo "  $waktu: $jumlah_guru guru mengajar\n";
            }
        }
    }

    /**
     * Tampilkan distribusi guru per mata pelajaran
     */
    private function tampilkanDistribusiGuru($guru_mapel)
    {
        echo "\n=== DISTRIBUSI GURU PER MATA PELAJARAN ===\n";
        
        $nama_mapel = DB::table('mata_pelajaran')->pluck('nama', 'id_mata_pelajaran')->toArray();

        foreach ($guru_mapel as $mapel_id => $guru_list) {
            $nama = $nama_mapel[$mapel_id] ?? "Mapel $mapel_id";
            $jumlah = count($guru_list);
            echo "$nama: $jumlah guru (ID: " . implode(', ', $guru_list) . ")\n";
        }
    }

    /**
     * Tampilkan ringkasan jadwal
     */
    private function tampilkanRingkasanJadwal($jumlah_kelas, $jumlah_hari)
    {
        echo "\n=== RINGKASAN JADWAL ===\n";
        echo "Jumlah Kelas: $jumlah_kelas\n";
        echo "Jumlah Hari: $jumlah_hari (Senin - Sabtu)\n";
        echo "Sesi per Hari: 6\n";
        echo "Total Sesi per Kelas per Minggu: " . ($jumlah_hari * 6) . "\n";
        echo "Total Jadwal yang Dibuat: " . ($jumlah_kelas * $jumlah_hari * 6) . "\n";
    }
}