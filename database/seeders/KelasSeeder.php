<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            [
                'id_kelas' => 1,
                'nama_kelas' => '7A',
                'tingkat' => '1',
                'id_guru' => 1, // Ahmad Wijaya (Matematika) sebagai wali kelas
                'id_tahun_ajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_kelas' => 2,
                'nama_kelas' => '7B',
                'tingkat' => '1',
                'id_guru' => 2, // Dewi Susanti (Bahasa Indonesia) sebagai wali kelas
                'id_tahun_ajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_kelas' => 3,
                'nama_kelas' => '7C',
                'tingkat' => '1',
                'id_guru' => 3, // Sarah Johnson (Bahasa Inggris) sebagai wali kelas
                'id_tahun_ajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_kelas' => 4,
                'nama_kelas' => '8A',
                'tingkat' => '2',
                'id_guru' => 4, // Rini Wulandari (IPA) sebagai wali kelas
                'id_tahun_ajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_kelas' => 5,
                'nama_kelas' => '8B',
                'tingkat' => '2',
                'id_guru' => 5, // Budi Santoso (PPKn) sebagai wali kelas
                'id_tahun_ajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_kelas' => 6,
                'nama_kelas' => '9A',
                'tingkat' => '3',
                'id_guru' => 6, // Siti Nurhaliza (IPS) sebagai wali kelas
                'id_tahun_ajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_kelas' => 7,
                'nama_kelas' => '9B',
                'tingkat' => '3',
                'id_guru' => 7, // Indira Sari (Seni Budaya) sebagai wali kelas
                'id_tahun_ajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('kelas')->insert($kelas);
    }
}