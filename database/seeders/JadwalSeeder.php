<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwal = [];
        $id = 1;
        
        // Define time sessions as requested
        $sesi = [
            1 => ['mulai' => '07:45:00', 'selesai' => '08:30:00'],
            2 => ['mulai' => '08:30:00', 'selesai' => '09:15:00'],
            3 => ['mulai' => '09:15:00', 'selesai' => '10:00:00'],
            4 => ['mulai' => '10:15:00', 'selesai' => '11:00:00'], // After 15 min break
            5 => ['mulai' => '11:00:00', 'selesai' => '11:45:00'],
            6 => ['mulai' => '11:45:00', 'selesai' => '12:30:00'],
        ];
        
        $hari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        
        // Get all classes
        $allKelas = DB::table('kelas')->get();
        if ($allKelas->isEmpty()) {
            $this->command->info('No classes found. Please seed the kelas table first.');
            return;
        }
        
        // Get active tahun ajaran
        $tahunAjaranAktif = DB::table('tahun_ajaran')->where('aktif', 1)->first();
        if (!$tahunAjaranAktif) {
            $this->command->info('No active tahun ajaran found. Using the first tahun ajaran available.');
            $tahunAjaranAktif = DB::table('tahun_ajaran')->first();
            
            if (!$tahunAjaranAktif) {
                $this->command->error('No tahun ajaran found. Please seed the tahun_ajaran table first.');
                return;
            }
        }
        $idTahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        
        // Get all teachers and subjects
        $allGuru = DB::table('guru')->get();
        if ($allGuru->isEmpty()) {
            $this->command->error('No teachers found. Please seed the guru table first.');
            return;
        }
        
        $allMapel = DB::table('mata_pelajaran')->get();
        if ($allMapel->isEmpty()) {
            $this->command->error('No subjects found. Please seed the mata_pelajaran table first.');
            return;
        }
        
        $now = Carbon::now();
        
        // For each class, create a schedule for each day and session
        foreach ($allKelas as $kelas) {
            foreach ($hari as $h) {
                foreach ($sesi as $s => $waktu) {
                    // Randomly assign a teacher and subject
                    $randomGuru = $allGuru[array_rand($allGuru->toArray())];
                    $randomMapel = $allMapel[array_rand($allMapel->toArray())];
                    
                    $jadwal[] = [
                        'id_kelas' => $kelas->id_kelas,
                        'id_mata_pelajaran' => $randomMapel->id_mata_pelajaran,
                        'id_guru' => $randomGuru->id_guru,
                        'id_tahun_ajaran' => $idTahunAjaran,
                        'hari' => $h,
                        'waktu_mulai' => $waktu['mulai'],
                        'waktu_selesai' => $waktu['selesai'],
                        'status' => 'aktif',
                        'dibuat_pada' => $now,
                        'dibuat_oleh' => 'system',
                        'diperbarui_pada' => $now,
                        'diperbarui_oleh' => 'system'
                    ];
                    
                    // Insert in batches of 100 to avoid memory issues
                    if (count($jadwal) >= 100) {
                        DB::table('jadwal')->insert($jadwal);
                        $jadwal = [];
                    }
                }
            }
        }
        
        // Insert any remaining records
        if (!empty($jadwal)) {
            DB::table('jadwal')->insert($jadwal);
        }
        
        $this->command->info('Jadwal seeded successfully!');
    }
}
