<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = [
            [
                'id_siswa' => 1,
                'nama' => 'Andi Pratama',
                'nis' => '2024001',
                'id_orangtua' => 1,
                'id_kelas' => 1,
                'id_tahun_ajaran' => 1,
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2012-05-15',
                'jenis_kelamin' => 'laki-laki',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_siswa' => 2,
                'nama' => 'Dina Fitriani',
                'nis' => '2024002',
                'id_orangtua' => 1,
                'id_kelas' => 1,
                'id_tahun_ajaran' => 1,
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2012-08-20',
                'jenis_kelamin' => 'perempuan',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_siswa' => 3,
                'nama' => 'Rizki Ramadhan',
                'nis' => '2024003',
                'id_orangtua' => 2,
                'id_kelas' => 2,
                'id_tahun_ajaran' => 1,
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2012-03-10',
                'jenis_kelamin' => 'laki-laki',
                'alamat' => 'Jl. Pahlawan No. 45, Jakarta Timur',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_siswa' => 4,
                'nama' => 'Maya Sari',
                'nis' => '2024004',
                'id_orangtua' => 2,
                'id_kelas' => 3,
                'id_tahun_ajaran' => 1,
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2011-11-25',
                'jenis_kelamin' => 'perempuan',
                'alamat' => 'Jl. Pahlawan No. 45, Jakarta Timur',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('siswa')->insert($siswa);
    }
}