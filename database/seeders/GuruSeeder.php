<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guru = [
            [
                'id_guru' => 1,
                'id_user' => 4,
                'nama_lengkap' => 'Ahmad Wijaya',
                'nip' => '198601202008011002',
                'nomor_telepon' => '081234567891',
                'bidang_studi' => 'Matematika',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 2,
                'id_user' => 5,
                'nama_lengkap' => 'Dewi Susanti',
                'nip' => '198703152009012003',
                'nomor_telepon' => '081234567892',
                'bidang_studi' => 'Bahasa Indonesia',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 3,
                'id_user' => 6,
                'nama_lengkap' => 'Rini Wulandari',
                'nip' => '198805102010012004',
                'nomor_telepon' => '081234567893',
                'bidang_studi' => 'IPA',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('guru')->insert($guru);
    }
}