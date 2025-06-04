<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Staf
            [
                'id_user' => 1,
                'username' => 'admin',
                'password' => Hash::make('password'),
                'id_role' => 1,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Orangtua
            [
                'id_user' => 2,
                'username' => 'budi_santoso',
                'password' => Hash::make('password'),
                'id_role' => 2,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 3,
                'username' => 'siti_rahayu',
                'password' => Hash::make('password'),
                'id_role' => 2,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru Utama
            [
                'id_user' => 4,
                'username' => 'ahmad_wijaya',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 5,
                'username' => 'dewi_susanti',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 6,
                'username' => 'sarah_johnson',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 7,
                'username' => 'rini_wulandari',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 8,
                'username' => 'budi_santoso_guru',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 9,
                'username' => 'siti_nurhaliza',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 10,
                'username' => 'indira_sari',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 11,
                'username' => 'yoga_pratama',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 12,
                'username' => 'andi_kurniawan',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru Tambahan Gelombang 1 (untuk menghindari konflik jadwal)
            [
                'id_user' => 20,
                'username' => 'sari_matematika',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 21,
                'username' => 'rina_indonesia',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 22,
                'username' => 'maya_english',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 23,
                'username' => 'doni_sains',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 24,
                'username' => 'lina_civics',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 25,
                'username' => 'eko_sosial',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 26,
                'username' => 'fitri_seni',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 27,
                'username' => 'agus_sport',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 28,
                'username' => 'nina_craft',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            // Guru Tambahan Gelombang 2 (untuk jadwal sabtu dan jam tambahan)
            [
                'id_user' => 30,
                'username' => 'hendra_math',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 31,
                'username' => 'lisa_bahasa',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 32,
                'username' => 'david_english',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 33,
                'username' => 'ratna_science',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 34,
                'username' => 'bambang_pkn',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 35,
                'username' => 'wati_ips',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 36,
                'username' => 'andi_art',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 37,
                'username' => 'rudi_sport',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 38,
                'username' => 'sinta_craft',
                'password' => Hash::make('password'),
                'id_role' => 3,
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('users')->insert($users);
    }
}