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
            // Guru Utama
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
                'nama_lengkap' => 'Sarah Johnson',
                'nip' => '198805102010012004',
                'nomor_telepon' => '081234567893',
                'bidang_studi' => 'Bahasa Inggris',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 4,
                'id_user' => 7,
                'nama_lengkap' => 'Rini Wulandari',
                'nip' => '199002252012012005',
                'nomor_telepon' => '081234567894',
                'bidang_studi' => 'Ilmu Pengetahuan Alam',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 5,
                'id_user' => 8,
                'nama_lengkap' => 'Budi Santoso',
                'nip' => '198904182011011006',
                'nomor_telepon' => '081234567895',
                'bidang_studi' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 6,
                'id_user' => 9,
                'nama_lengkap' => 'Siti Nurhaliza',
                'nip' => '199106302013012007',
                'nomor_telepon' => '081234567896',
                'bidang_studi' => 'Ilmu Pengetahuan Sosial',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 7,
                'id_user' => 10,
                'nama_lengkap' => 'Indira Sari',
                'nip' => '199208152014012008',
                'nomor_telepon' => '081234567897',
                'bidang_studi' => 'Seni Budaya',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 8,
                'id_user' => 11,
                'nama_lengkap' => 'Yoga Pratama',
                'nip' => '199310202015011009',
                'nomor_telepon' => '081234567898',
                'bidang_studi' => 'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 9,
                'id_user' => 12,
                'nama_lengkap' => 'Andi Kurniawan',
                'nip' => '199405122016011010',
                'nomor_telepon' => '081234567899',
                'bidang_studi' => 'Prakarya',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru Tambahan Gelombang 1
            [
                'id_guru' => 10,
                'id_user' => 20,
                'nama_lengkap' => 'Sari Matematika',
                'nip' => '199507152017012010',
                'nomor_telepon' => '081234567900',
                'bidang_studi' => 'Matematika',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 11,
                'id_user' => 21,
                'nama_lengkap' => 'Rina Indonesia',
                'nip' => '199608202018012011',
                'nomor_telepon' => '081234567901',
                'bidang_studi' => 'Bahasa Indonesia',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 12,
                'id_user' => 22,
                'nama_lengkap' => 'Maya English',
                'nip' => '199709102019012012',
                'nomor_telepon' => '081234567902',
                'bidang_studi' => 'Bahasa Inggris',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 13,
                'id_user' => 23,
                'nama_lengkap' => 'Doni Sains',
                'nip' => '199810252020011013',
                'nomor_telepon' => '081234567903',
                'bidang_studi' => 'Ilmu Pengetahuan Alam',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 14,
                'id_user' => 24,
                'nama_lengkap' => 'Lina Civics',
                'nip' => '199911152021012014',
                'nomor_telepon' => '081234567904',
                'bidang_studi' => 'PPKn',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 15,
                'id_user' => 25,
                'nama_lengkap' => 'Eko Sosial',
                'nip' => '200012302022011015',
                'nomor_telepon' => '081234567905',
                'bidang_studi' => 'Ilmu Pengetahuan Sosial',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 16,
                'id_user' => 26,
                'nama_lengkap' => 'Fitri Seni',
                'nip' => '200101182023012016',
                'nomor_telepon' => '081234567906',
                'bidang_studi' => 'Seni Budaya',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 17,
                'id_user' => 27,
                'nama_lengkap' => 'Agus Sport',
                'nip' => '200202052024011017',
                'nomor_telepon' => '081234567907',
                'bidang_studi' => 'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 18,
                'id_user' => 28,
                'nama_lengkap' => 'Nina Craft',
                'nip' => '200303122025012018',
                'nomor_telepon' => '081234567908',
                'bidang_studi' => 'Prakarya',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru Tambahan Gelombang 2
            [
                'id_guru' => 19,
                'id_user' => 30,
                'nama_lengkap' => 'Hendra Matematika',
                'nip' => '200404152026011019',
                'nomor_telepon' => '081234567910',
                'bidang_studi' => 'Matematika',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 20,
                'id_user' => 31,
                'nama_lengkap' => 'Lisa Bahasa',
                'nip' => '200505202027012020',
                'nomor_telepon' => '081234567911',
                'bidang_studi' => 'Bahasa Indonesia',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 21,
                'id_user' => 32,
                'nama_lengkap' => 'David English',
                'nip' => '200606102028011021',
                'nomor_telepon' => '081234567912',
                'bidang_studi' => 'Bahasa Inggris',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 22,
                'id_user' => 33,
                'nama_lengkap' => 'Ratna Science',
                'nip' => '200707252029012022',
                'nomor_telepon' => '081234567913',
                'bidang_studi' => 'Ilmu Pengetahuan Alam',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 23,
                'id_user' => 34,
                'nama_lengkap' => 'Bambang PKN',
                'nip' => '200808182030011023',
                'nomor_telepon' => '081234567914',
                'bidang_studi' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 24,
                'id_user' => 35,
                'nama_lengkap' => 'Wati IPS',
                'nip' => '200909302031012024',
                'nomor_telepon' => '081234567915',
                'bidang_studi' => 'Ilmu Pengetahuan Sosial',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 25,
                'id_user' => 36,
                'nama_lengkap' => 'Andi Art',
                'nip' => '201010152032011025',
                'nomor_telepon' => '081234567916',
                'bidang_studi' => 'Seni Budaya',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 26,
                'id_user' => 37,
                'nama_lengkap' => 'Rudi Sport',
                'nip' => '201111052033011026',
                'nomor_telepon' => '081234567917',
                'bidang_studi' => 'Pendidikan Jasmani, Olahraga dan Kesehatan',
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_guru' => 27,
                'id_user' => 38,
                'nama_lengkap' => 'Sinta Craft',
                'nip' => '201212122034012027',
                'nomor_telepon' => '081234567918',
                'bidang_studi' => 'Prakarya',
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