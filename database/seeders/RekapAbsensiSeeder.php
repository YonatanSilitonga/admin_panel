<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rekapAbsensi = [
            [
                'id_rekap' => 1,
                'id_siswa' => 1,
                'id_kelas' => 1,
                'bulan' => '05',
                'tahun' => 2025,
                'jumlah_hadir' => 18,
                'jumlah_sakit' => 2,
                'jumlah_izin' => 0,
                'jumlah_alpa' => 0,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_rekap' => 2,
                'id_siswa' => 2,
                'id_kelas' => 1,
                'bulan' => '05',
                'tahun' => 2025,
                'jumlah_hadir' => 17,
                'jumlah_sakit' => 0,
                'jumlah_izin' => 3,
                'jumlah_alpa' => 0,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_rekap' => 3,
                'id_siswa' => 3,
                'id_kelas' => 2,
                'bulan' => '05',
                'tahun' => 2025,
                'jumlah_hadir' => 19,
                'jumlah_sakit' => 0,
                'jumlah_izin' => 0,
                'jumlah_alpa' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_rekap' => 4,
                'id_siswa' => 4,
                'id_kelas' => 3,
                'bulan' => '05',
                'tahun' => 2025,
                'jumlah_hadir' => 20,
                'jumlah_sakit' => 0,
                'jumlah_izin' => 0,
                'jumlah_alpa' => 0,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('rekap_absensi')->insert($rekapAbsensi);
    }
}