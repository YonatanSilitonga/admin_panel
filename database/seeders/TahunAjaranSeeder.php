<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaran = [
            [
                'id_tahun_ajaran' => 1,
                'nama_tahun_ajaran' => 'Tahun Ajaran 2024/2025',
                'tanggal_mulai' => '2024-07-15',
                'tanggal_selesai' => '2025-06-30',
                'aktif' => 1,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_tahun_ajaran' => 2,
                'nama_tahun_ajaran' => 'Tahun Ajaran 2025/2026',
                'tanggal_mulai' => '2025-07-15',
                'tanggal_selesai' => '2026-06-30',
                'aktif' => 0,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('tahun_ajaran')->insert($tahunAjaran);
    }
}