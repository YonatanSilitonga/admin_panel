<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_kelas',
        'id_mata_pelajaran',
        'id_guru',
        'id_tahun_ajaran',
        'hari',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'dibuat_oleh',
        'diperbarui_oleh'
    ];

    // Jangan cast waktu sebagai datetime, biarkan sebagai time string
    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // Accessor untuk format waktu yang konsisten
    public function getWaktuMulaiAttribute($value)
    {
        // Pastikan format waktu konsisten (HH:MM:SS)
        if ($value && strlen($value) === 5) {
            return $value . ':00';
        }
        return $value;
    }

    public function getWaktuSelesaiAttribute($value)
    {
        // Pastikan format waktu konsisten (HH:MM:SS)
        if ($value && strlen($value) === 5) {
            return $value . ':00';
        }
        return $value;
    }

    // Mutator untuk menyimpan waktu dengan format yang benar
    public function setWaktuMulaiAttribute($value)
    {
        $this->attributes['waktu_mulai'] = $value;
    }

    public function setWaktuSelesaiAttribute($value)
    {
        $this->attributes['waktu_selesai'] = $value;
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
    
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Scope untuk filter berdasarkan kelas dan tahun ajaran
     */
    public function scopeForKelas($query, $kelasId, $tahunAjaranId = null)
    {
        $query->where('id_kelas', $kelasId);
        
        if ($tahunAjaranId) {
            $query->where('id_tahun_ajaran', $tahunAjaranId);
        }
        
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk filter berdasarkan hari
     */
    public function scopeForHari($query, $hari)
    {
        return $query->where('hari', strtolower($hari));
    }

    /**
     * Scope untuk filter berdasarkan waktu
     */
    public function scopeForWaktu($query, $waktuMulai, $waktuSelesai = null)
    {
        $query->where('waktu_mulai', $waktuMulai);
        
        if ($waktuSelesai) {
            $query->where('waktu_selesai', $waktuSelesai);
        }
        
        return $query;
    }

    /**
     * Get sesi number berdasarkan waktu mulai
     */
    public function getSesiNumberAttribute()
    {
        $sesiWaktu = [
            '07:45:00' => 1,
            '08:30:00' => 2,
            '09:15:00' => 3,
            '10:15:00' => 4,
            '11:00:00' => 5,
            '11:45:00' => 6,
        ];

        return $sesiWaktu[$this->waktu_mulai] ?? null;
    }

    /**
     * Get formatted time range
     */
    public function getWaktuRangeAttribute()
    {
        $mulai = substr($this->waktu_mulai, 0, 5);
        $selesai = substr($this->waktu_selesai, 0, 5);
        return "{$mulai} - {$selesai}";
    }

    /**
     * Get formatted hari
     */
    public function getHariFormattedAttribute()
    {
        return ucfirst($this->hari);
    }

    /**
     * Check for scheduling conflicts with other classes
     */
    public function getConflicts()
    {
        $query = self::where('hari', $this->hari)
            ->where('id_tahun_ajaran', $this->id_tahun_ajaran)
            ->where(function($query) {
                // Check for time overlaps
                $query->where(function($q) {
                    $q->where('waktu_mulai', '>=', $this->waktu_mulai)
                      ->where('waktu_mulai', '<', $this->waktu_selesai);
                })->orWhere(function($q) {
                    $q->where('waktu_selesai', '>', $this->waktu_mulai)
                      ->where('waktu_selesai', '<=', $this->waktu_selesai);
                })->orWhere(function($q) {
                    $q->where('waktu_mulai', '<=', $this->waktu_mulai)
                      ->where('waktu_selesai', '>=', $this->waktu_selesai);
                });
            })
            ->where(function($query) {
                // Either same class or same teacher
                $query->where('id_kelas', $this->id_kelas)
                      ->orWhere('id_guru', $this->id_guru);
            })
            ->where('status', 'aktif');
            
        // If this is an existing jadwal (has an ID), exclude it from conflict check
        if (isset($this->id_jadwal)) {
            $query->where('id_jadwal', '!=', $this->id_jadwal);
        }
        
        return $query->with(['kelas', 'mataPelajaran', 'guru'])->get();
    }
    
    /**
     * Check if this schedule has conflicts
     */
    public function hasConflicts()
    {
        return $this->getConflicts()->count() > 0;
    }

    /**
     * Static method untuk mendapatkan jadwal berdasarkan kelas dan format grid
     */
    public static function getJadwalGrid($kelasId, $tahunAjaranId = null)
    {
        $query = self::with(['mataPelajaran', 'guru'])
            ->forKelas($kelasId, $tahunAjaranId);

        $jadwalList = $query->get();

        // Organize by hari and sesi
        $jadwalGrid = [];
        $sesiWaktu = [
            1 => '07:45:00',
            2 => '08:30:00', 
            3 => '09:15:00',
            4 => '10:15:00',
            5 => '11:00:00',
            6 => '11:45:00',
        ];

        foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari) {
            $jadwalGrid[$hari] = [];
            
            for ($sesi = 1; $sesi <= 6; $sesi++) {
                $waktuMulai = $sesiWaktu[$sesi];
                
                $jadwal = $jadwalList->first(function($item) use ($hari, $waktuMulai) {
                    return $item->hari === $hari && $item->waktu_mulai === $waktuMulai;
                });

                if ($jadwal) {
                    $jadwalGrid[$hari][$sesi] = [
                        'id_jadwal' => $jadwal->id_jadwal,
                        'id_mata_pelajaran' => $jadwal->id_mata_pelajaran,
                        'id_guru' => $jadwal->id_guru,
                        'mata_pelajaran_nama' => $jadwal->mataPelajaran->nama,
                        'guru_nama' => $jadwal->guru->nama_lengkap,
                        'waktu_mulai' => $jadwal->waktu_mulai,
                        'waktu_selesai' => $jadwal->waktu_selesai,
                    ];
                }
            }
        }

        return $jadwalGrid;
    }
}
