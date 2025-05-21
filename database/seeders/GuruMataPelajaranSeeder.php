<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuruMataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruMataPelajaran = [
            [
                'id_guru_mata_pelajaran' => 1,
                'id_guru' => 1,
                'id_mata_pelajaran' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru_mata_pelajaran' => 2,
                'id_guru' => 2,
                'id_mata_pelajaran' => 2,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru_mata_pelajaran' => 3,
                'id_guru' => 3,
                'id_mata_pelajaran' => 3,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru_mata_pelajaran' => 4,
                'id_guru' => 1,
                'id_mata_pelajaran' => 4,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru_mata_pelajaran' => 5,
                'id_guru' => 2,
                'id_mata_pelajaran' => 5,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('guru_mata_pelajaran')->insert($guruMataPelajaran);
    }
}