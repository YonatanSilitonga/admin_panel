<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suratIzin = [
            [
                'id_surat_izin' => 1,
                'id_siswa' => 1,
                'id_orangtua' => 1,
                'jenis' => 'sakit',
                'tanggal_mulai' => '2025-05-05',
                'tanggal_selesai' => '2025-05-06',
                'alasan' => 'Sakit demam dan flu',
                'file_lampiran' => 'surat_dokter_1.pdf',
                'status' => 'disetujui',
                'dibuat_pada' => Carbon::now()->subDays(5),
                'dibuat_oleh' => 'Budi Santoso',
                'diperbarui_pada' => Carbon::now()->subDays(4),
                'diperbarui_oleh' => 'Ahmad Wijaya'
            ],
            [
                'id_surat_izin' => 2,
                'id_siswa' => 2,
                'id_orangtua' => 1,
                'jenis' => 'izin',
                'tanggal_mulai' => '2025-05-10',
                'tanggal_selesai' => '2025-05-10',
                'alasan' => 'Acara keluarga',
                'file_lampiran' => null,
                'status' => 'disetujui',
                'dibuat_pada' => Carbon::now()->subDays(3),
                'dibuat_oleh' => 'Budi Santoso',
                'diperbarui_pada' => Carbon::now()->subDays(2),
                'diperbarui_oleh' => 'Ahmad Wijaya'
            ],
            [
                'id_surat_izin' => 3,
                'id_siswa' => 3,
                'id_orangtua' => 2,
                'jenis' => 'sakit',
                'tanggal_mulai' => '2025-05-15',
                'tanggal_selesai' => '2025-05-17',
                'alasan' => 'Sakit perut',
                'file_lampiran' => 'surat_dokter_2.pdf',
                'status' => 'menunggu',
                'dibuat_pada' => Carbon::now()->subDay(),
                'dibuat_oleh' => 'Siti Rahayu',
                'diperbarui_pada' => Carbon::now()->subDay(),
                'diperbarui_oleh' => 'Siti Rahayu'
            ],
        ];

        DB::table('surat_izin')->insert($suratIzin);
    }
}