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
            // Guru 1 (Ahmad Wijaya) - Matematika
            [
                'id_guru_mata_pelajaran' => 1,
                'id_guru' => 1,
                'id_mata_pelajaran' => 1, // Matematika
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 2 (Dewi Susanti) - Bahasa Indonesia
            [
                'id_guru_mata_pelajaran' => 2,
                'id_guru' => 2,
                'id_mata_pelajaran' => 2, // Bahasa Indonesia
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 3 (Sarah Johnson) - Bahasa Inggris
            [
                'id_guru_mata_pelajaran' => 3,
                'id_guru' => 3,
                'id_mata_pelajaran' => 3, // Bahasa Inggris
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 4 (Rini Wulandari) - IPA
            [
                'id_guru_mata_pelajaran' => 4,
                'id_guru' => 4,
                'id_mata_pelajaran' => 4, // IPA
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 5 (Budi Santoso) - PPKn
            [
                'id_guru_mata_pelajaran' => 5,
                'id_guru' => 5,
                'id_mata_pelajaran' => 5, // PPKn
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 6 (Siti Nurhaliza) - IPS
            [
                'id_guru_mata_pelajaran' => 6,
                'id_guru' => 6,
                'id_mata_pelajaran' => 6, // IPS
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 7 (Indira Sari) - Seni Budaya
            [
                'id_guru_mata_pelajaran' => 7,
                'id_guru' => 7,
                'id_mata_pelajaran' => 7, // Seni Budaya
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 8 (Yoga Pratama) - PJOK
            [
                'id_guru_mata_pelajaran' => 8,
                'id_guru' => 8,
                'id_mata_pelajaran' => 8, // PJOK
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 9 (Andi Kurniawan) - Prakarya
            [
                'id_guru_mata_pelajaran' => 9,
                'id_guru' => 9,
                'id_mata_pelajaran' => 9, // Prakarya
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Tambahan: Beberapa guru bisa mengajar lebih dari satu mata pelajaran
            // Guru Ahmad (Matematika) juga bisa mengajar IPA
            [
                'id_guru_mata_pelajaran' => 10,
                'id_guru' => 1,
                'id_mata_pelajaran' => 4, // IPA
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru Dewi (Bahasa Indonesia) juga bisa mengajar Seni Budaya
            [
                'id_guru_mata_pelajaran' => 11,
                'id_guru' => 2,
                'id_mata_pelajaran' => 7, // Seni Budaya
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('guru_mata_pelajaran')->insert($guruMataPelajaran);
    }
}