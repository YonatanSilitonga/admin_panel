<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanggalMulai = Carbon::create(2025, 5, 1);
        $absensi = [];
        $counter = 1;

        // Generate absensi untuk 2 minggu terakhir
        for ($i = 0; $i < 10; $i++) {
            $tanggal = $tanggalMulai->copy()->addDays($i);
            
            // Skip hari Sabtu dan Minggu
            if ($tanggal->dayOfWeek == Carbon::SATURDAY || $tanggal->dayOfWeek == Carbon::SUNDAY) {
                continue;
            }
            
            // Konversi hari ke format database
            $hariIndonesia = '';
            switch ($tanggal->dayOfWeek) {
                case Carbon::MONDAY:
                    $hariIndonesia = 'senin';
                    break;
                case Carbon::TUESDAY:
                    $hariIndonesia = 'selasa';
                    break;
                case Carbon::WEDNESDAY:
                    $hariIndonesia = 'rabu';
                    break;
                case Carbon::THURSDAY:
                    $hariIndonesia = 'kamis';
                    break;
                case Carbon::FRIDAY:
                    $hariIndonesia = 'jumat';
                    break;
            }
            
            // Ambil jadwal untuk hari ini
            for ($jadwalId = 1; $jadwalId <= 5; $jadwalId++) {
                // Siswa 1 dan 2 di kelas 1
                if ($jadwalId <= 3) {
                    // Siswa 1
                    $absensi[] = [
                        'id_absensi' => $counter++,
                        'id_siswa' => 1,
                        'id_jadwal' => $jadwalId,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'status' => $i % 5 == 0 ? 'sakit' : 'hadir',
                        'catatan' => $i % 5 == 0 ? 'Sakit flu' : null,
                        'dibuat_pada' => Carbon::now(),
                        'dibuat_oleh' => 'system',
                        'diperbarui_pada' => Carbon::now(),
                        'diperbarui_oleh' => 'system'
                    ];
                    
                    // Siswa 2
                    $absensi[] = [
                        'id_absensi' => $counter++,
                        'id_siswa' => 2,
                        'id_jadwal' => $jadwalId,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'status' => $i % 7 == 0 ? 'izin' : 'hadir',
                        'catatan' => $i % 7 == 0 ? 'Izin keluarga' : null,
                        'dibuat_pada' => Carbon::now(),
                        'dibuat_oleh' => 'system',
                        'diperbarui_pada' => Carbon::now(),
                        'diperbarui_oleh' => 'system'
                    ];
                }
                
                // Siswa 3 di kelas 2
                if ($jadwalId == 4) {
                    $absensi[] = [
                        'id_absensi' => $counter++,
                        'id_siswa' => 3,
                        'id_jadwal' => $jadwalId,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'status' => $i % 8 == 0 ? 'alpa' : 'hadir',
                        'catatan' => $i % 8 == 0 ? 'Tidak ada keterangan' : null,
                        'dibuat_pada' => Carbon::now(),
                        'dibuat_oleh' => 'system',
                        'diperbarui_pada' => Carbon::now(),
                        'diperbarui_oleh' => 'system'
                    ];
                }
                
                // Siswa 4 di kelas 3
                if ($jadwalId == 5) {
                    $absensi[] = [
                        'id_absensi' => $counter++,
                        'id_siswa' => 4,
                        'id_jadwal' => $jadwalId,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'status' => 'hadir',
                        'catatan' => null,
                        'dibuat_pada' => Carbon::now(),
                        'dibuat_oleh' => 'system',
                        'diperbarui_pada' => Carbon::now(),
                        'diperbarui_oleh' => 'system'
                    ];
                }
            }
        }

        DB::table('absensi')->insert($absensi);
    }
}