<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwal = [
            [
                'id_jadwal' => 1,
                'id_kelas' => 1,
                'id_mata_pelajaran' => 1,
                'id_guru' => 1,
                'id_tahun_ajaran' => 1,
                'hari' => 'senin',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '09:00:00',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_jadwal' => 2,
                'id_kelas' => 1,
                'id_mata_pelajaran' => 2,
                'id_guru' => 2,
                'id_tahun_ajaran' => 1,
                'hari' => 'senin',
                'waktu_mulai' => '09:15:00',
                'waktu_selesai' => '10:45:00',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_jadwal' => 3,
                'id_kelas' => 1,
                'id_mata_pelajaran' => 3,
                'id_guru' => 3,
                'id_tahun_ajaran' => 1,
                'hari' => 'selasa',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '09:00:00',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_jadwal' => 4,
                'id_kelas' => 2,
                'id_mata_pelajaran' => 1,
                'id_guru' => 1,
                'id_tahun_ajaran' => 1,
                'hari' => 'rabu',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '09:00:00',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_jadwal' => 5,
                'id_kelas' => 3,
                'id_mata_pelajaran' => 2,
                'id_guru' => 2,
                'id_tahun_ajaran' => 1,
                'hari' => 'kamis',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '09:00:00',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('jadwal')->insert($jadwal);
    }
}