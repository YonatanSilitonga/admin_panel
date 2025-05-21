<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrangtuaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orangtua = [
            [
                'id_orangtua' => 1,
                'id_user' => 2,
                'nama_lengkap' => 'Budi Santoso',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
                'nomor_telepon' => '081234567894',
                'pekerjaan' => 'Wiraswasta',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_orangtua' => 2,
                'id_user' => 3,
                'nama_lengkap' => 'Siti Rahayu',
                'alamat' => 'Jl. Pahlawan No. 45, Jakarta Timur',
                'nomor_telepon' => '081234567895',
                'pekerjaan' => 'Guru',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('orangtua')->insert($orangtua);
    }
}