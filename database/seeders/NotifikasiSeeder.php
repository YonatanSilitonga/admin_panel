<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifikasi = [
            [
                'id_notifikasi' => 1,
                'id_user' => 1,
                'judul' => 'Selamat Datang',
                'pesan' => 'Selamat datang di sistem absensi sekolah.',
                'tipe' => 'info',
                'dibaca' => 1,
                'waktu_dibaca' => Carbon::now()->subDays(5),
                'dibuat_pada' => Carbon::now()->subDays(6),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now()->subDays(5),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_notifikasi' => 2,
                'id_user' => 2,
                'judul' => 'Surat Izin Disetujui',
                'pesan' => 'Surat izin untuk Andi Pratama telah disetujui.',
                'tipe' => 'success',
                'dibaca' => 1,
                'waktu_dibaca' => Carbon::now()->subDays(3),
                'dibuat_pada' => Carbon::now()->subDays(4),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now()->subDays(3),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_notifikasi' => 3,
                'id_user' => 3,
                'judul' => 'Surat Izin Menunggu Persetujuan',
                'pesan' => 'Surat izin untuk Rizki Ramadhan sedang menunggu persetujuan.',
                'tipe' => 'warning',
                'dibaca' => 0,
                'waktu_dibaca' => null,
                'dibuat_pada' => Carbon::now()->subDay(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now()->subDay(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_notifikasi' => 4,
                'id_user' => 4,
                'judul' => 'Jadwal Mengajar Baru',
                'pesan' => 'Anda memiliki jadwal mengajar baru untuk kelas 7A pada hari Senin.',
                'tipe' => 'info',
                'dibaca' => 1,
                'waktu_dibaca' => Carbon::now()->subDays(2),
                'dibuat_pada' => Carbon::now()->subDays(3),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now()->subDays(2),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_notifikasi' => 5,
                'id_user' => 5,
                'judul' => 'Pengumuman Rapat Guru',
                'pesan' => 'Rapat guru akan diadakan pada tanggal 25 Mei 2025 pukul 14:00.',
                'tipe' => 'info',
                'dibaca' => 0,
                'waktu_dibaca' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('notifikasi')->insert($notifikasi);
    }
}