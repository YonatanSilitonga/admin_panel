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
            // Guru Utama (ID 1-9)
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

            // Guru Tambahan Gelombang 1 (ID 10-18)
            // Guru 10 (Sari Matematika) - Matematika
            [
                'id_guru_mata_pelajaran' => 10,
                'id_guru' => 10,
                'id_mata_pelajaran' => 1, // Matematika
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 11 (Rina Indonesia) - Bahasa Indonesia
            [
                'id_guru_mata_pelajaran' => 11,
                'id_guru' => 11,
                'id_mata_pelajaran' => 2, // Bahasa Indonesia
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 12 (Maya English) - Bahasa Inggris
            [
                'id_guru_mata_pelajaran' => 12,
                'id_guru' => 12,
                'id_mata_pelajaran' => 3, // Bahasa Inggris
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 13 (Doni Sains) - IPA
            [
                'id_guru_mata_pelajaran' => 13,
                'id_guru' => 13,
                'id_mata_pelajaran' => 4, // IPA
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 14 (Lina Civics) - PPKn
            [
                'id_guru_mata_pelajaran' => 14,
                'id_guru' => 14,
                'id_mata_pelajaran' => 5, // PPKn
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 15 (Eko Sosial) - IPS
            [
                'id_guru_mata_pelajaran' => 15,
                'id_guru' => 15,
                'id_mata_pelajaran' => 6, // IPS
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 16 (Fitri Seni) - Seni Budaya
            [
                'id_guru_mata_pelajaran' => 16,
                'id_guru' => 16,
                'id_mata_pelajaran' => 7, // Seni Budaya
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 17 (Agus Sport) - PJOK
            [
                'id_guru_mata_pelajaran' => 17,
                'id_guru' => 17,
                'id_mata_pelajaran' => 8, // PJOK
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 18 (Nina Craft) - Prakarya
            [
                'id_guru_mata_pelajaran' => 18,
                'id_guru' => 18,
                'id_mata_pelajaran' => 9, // Prakarya
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],

            // Guru Tambahan Gelombang 2 (ID 19-27)
            // Guru 19 (Hendra Matematika) - Matematika
            [
                'id_guru_mata_pelajaran' => 19,
                'id_guru' => 19,
                'id_mata_pelajaran' => 1, // Matematika
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 20 (Lisa Bahasa) - Bahasa Indonesia
            [
                'id_guru_mata_pelajaran' => 20,
                'id_guru' => 20,
                'id_mata_pelajaran' => 2, // Bahasa Indonesia
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 21 (David English) - Bahasa Inggris
            [
                'id_guru_mata_pelajaran' => 21,
                'id_guru' => 21,
                'id_mata_pelajaran' => 3, // Bahasa Inggris
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 22 (Ratna Science) - IPA
            [
                'id_guru_mata_pelajaran' => 22,
                'id_guru' => 22,
                'id_mata_pelajaran' => 4, // IPA
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 23 (Bambang PKN) - PPKn
            [
                'id_guru_mata_pelajaran' => 23,
                'id_guru' => 23,
                'id_mata_pelajaran' => 5, // PPKn
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 24 (Wati IPS) - IPS
            [
                'id_guru_mata_pelajaran' => 24,
                'id_guru' => 24,
                'id_mata_pelajaran' => 6, // IPS
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 25 (Andi Art) - Seni Budaya
            [
                'id_guru_mata_pelajaran' => 25,
                'id_guru' => 25,
                'id_mata_pelajaran' => 7, // Seni Budaya
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 26 (Rudi Sport) - PJOK
            [
                'id_guru_mata_pelajaran' => 26,
                'id_guru' => 26,
                'id_mata_pelajaran' => 8, // PJOK
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru 27 (Sinta Craft) - Prakarya
            [
                'id_guru_mata_pelajaran' => 27,
                'id_guru' => 27,
                'id_mata_pelajaran' => 9, // Prakarya
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('guru_mata_pelajaran')->insert($guruMataPelajaran);
    }
}