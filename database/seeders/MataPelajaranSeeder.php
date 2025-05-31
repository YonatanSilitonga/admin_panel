<?php 
 
namespace Database\Seeders; 
 
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; 
 
class MataPelajaranSeeder extends Seeder 
{ 
    /** 
     * Run the database seeds. 
     */ 
    public function run(): void 
    { 
        $mataPelajaran = [ 
            [ 
                'id_mata_pelajaran' => 1, 
                'nama' => 'Matematika', 
                'kode' => 'MTK', 
                'deskripsi' => 'Pelajaran tentang ilmu hitung dan logika', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
            [ 
                'id_mata_pelajaran' => 2, 
                'nama' => 'Bahasa Indonesia', 
                'kode' => 'BIN', 
                'deskripsi' => 'Pelajaran tentang bahasa dan sastra Indonesia', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
            [ 
                'id_mata_pelajaran' => 3, 
                'nama' => 'Bahasa Inggris', 
                'kode' => 'BIG', 
                'deskripsi' => 'Pelajaran tentang bahasa Inggris', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
            [ 
                'id_mata_pelajaran' => 4, 
                'nama' => 'Ilmu Pengetahuan Alam', 
                'kode' => 'IPA', 
                'deskripsi' => 'Pelajaran tentang ilmu alam dan sains', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
            [ 
                'id_mata_pelajaran' => 5, 
                'nama' => 'Pendidikan Pancasila dan Kewarganegaraan', 
                'kode' => 'PKN', 
                'deskripsi' => 'Pelajaran tentang nilai-nilai Pancasila dan kewarganegaraan', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
            [ 
                'id_mata_pelajaran' => 6, 
                'nama' => 'Ilmu Pengetahuan Sosial', 
                'kode' => 'IPS', 
                'deskripsi' => 'Pelajaran tentang ilmu sosial dan masyarakat', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
            [ 
                'id_mata_pelajaran' => 7, 
                'nama' => 'Seni Budaya', 
                'kode' => 'SBD', 
                'deskripsi' => 'Pelajaran tentang seni dan budaya Indonesia', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
            [ 
                'id_mata_pelajaran' => 8, 
                'nama' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 
                'kode' => 'PJK', 
                'deskripsi' => 'Pelajaran tentang pendidikan jasmani, olahraga dan kesehatan', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
            [ 
                'id_mata_pelajaran' => 9, 
                'nama' => 'Prakarya', 
                'kode' => 'PKY', 
                'deskripsi' => 'Pelajaran tentang keterampilan dan prakarya', 
                'dibuat_pada' => Carbon::now(), 
                'dibuat_oleh' => 'system', 
                'diperbarui_pada' => Carbon::now(), 
                'diperbarui_oleh' => 'system' 
            ], 
        ];  
 
        DB::table('mata_pelajaran')->insert($mataPelajaran); 
    } 
}