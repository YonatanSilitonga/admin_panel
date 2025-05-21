<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StafSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staf = [
            [
                'id_staf' => 1,
                'id_user' => 1,
                'nama_lengkap' => 'Bambang Suryanto',
                'nip' => '198501152010011001',
                'nomor_telepon' => '081234567890',
                'jabatan' => 'Administrator Sistem',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('staf')->insert($staf);
    }
}