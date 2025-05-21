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
            // Guru
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
        ];

        DB::table('users')->insert($users);
    }
}