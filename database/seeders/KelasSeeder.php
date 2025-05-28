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
                'id_guru' => 1,
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
                'id_guru' => 2,
                'id_tahun_ajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_kelas' => 3,
                'nama_kelas' => '8A',
                'tingkat' => '2',
                'id_guru' => 3,
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