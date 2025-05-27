<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Siswa extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'aktif';
    const STATUS_INACTIVE = 'nonaktif';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'siswa';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_siswa';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The column name for the created at timestamp.
     *
     * @var string
     */
    const CREATED_AT = 'dibuat_pada';

    /**
     * The column name for the updated at timestamp.
     *
     * @var string
     */
    const UPDATED_AT = 'diperbarui_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'nis',
        'id_orangtua',
        'id_kelas',
        'id_tahun_ajaran',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'status',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Get the class that the student belongs to.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Get the parent of the student.
     */
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'id_orangtua', 'id_orangtua');
    }

    /**
     * Get the academic year the student is enrolled in.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    /**
     * Get the attendances for the student.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Get the permission letters for the student.
     */
    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Update status based on class status
     * 
     * @return void
     */
    public function updateStatusBasedOnClass()
    {
        // If no class is assigned, don't change status
        if (!$this->kelas) {
            return;
        }
        
        // Get the class's academic year
        $tahunAjaran = $this->kelas->tahunAjaran;
        
        // Determine new status based on academic year
        $newStatus = ($tahunAjaran && $tahunAjaran->aktif) 
            ? self::STATUS_ACTIVE 
            : self::STATUS_INACTIVE;
        
        // Only proceed if status will change
        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            
            // Update the tahun_ajaran_id to match the class's academic year
            if ($tahunAjaran) {
                $this->id_tahun_ajaran = $tahunAjaran->id_tahun_ajaran;
            }
            
            $this->diperbarui_pada = now();
            $this->diperbarui_oleh = Auth::user() ? Auth::user()->username : 'system';
            $this->save();
        }
    }
    
    /**
     * Get status badge HTML
     * 
     * @return string
     */
    public function getStatusBadgeHtml()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                return '<span class="badge bg-success">Aktif</span>';
            case self::STATUS_INACTIVE:
                return '<span class="badge bg-secondary">Non-Aktif</span>';
            default:
                return '<span class="badge bg-light text-dark">Unknown</span>';
        }
    }
    
    /**
     * Override the save method to update status when class changes
     */
    public function save(array $options = [])
    {
        $wasNew = !$this->exists;
        $oldKelasId = $this->getOriginal('id_kelas');
        $oldParentId = $this->getOriginal('id_orangtua');
        $oldStatus = $this->getOriginal('status');
        
        // Set default values for audit fields if creating new record
        if ($wasNew) {
            $this->dibuat_pada = $this->dibuat_pada ?: now();
            $this->dibuat_oleh = $this->dibuat_oleh ?: (Auth::user() ? Auth::user()->username : 'system');
        }
        
        // Always update modified fields
        $this->diperbarui_pada = now();
        $this->diperbarui_oleh = Auth::user() ? Auth::user()->username : 'system';
        
        $result = parent::save($options);
        
        // If class has changed, update status based on new class
        if (($wasNew || $this->isDirty('id_kelas')) && $this->id_kelas) {
            $this->updateStatusBasedOnClass();
        }
        
        // Update parent status if needed
        $shouldUpdateParent = false;
        
        // If parent ID has changed, update both old and new parent statuses
        if (!$wasNew && $oldParentId !== $this->id_orangtua) {
            $shouldUpdateParent = true;
            
            // Update old parent status
            if ($oldParentId) {
                $oldParent = OrangTua::find($oldParentId);
                if ($oldParent) {
                    $oldParent->updateStatusBasedOnChildren();
                }
            }
        }
        
        // If student status has changed, update current parent status
        if (!$wasNew && $oldStatus !== $this->status) {
            $shouldUpdateParent = true;
        }
        
        // Update current parent status if needed
        if (($shouldUpdateParent || $wasNew) && $this->id_orangtua) {
            $parent = OrangTua::find($this->id_orangtua);
            if ($parent) {
                $parent->updateStatusBasedOnChildren();
            }
        }
        
        return $result;
    }
    
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // When a student is deleted, update parent status
        static::deleted(function ($siswa) {
            if ($siswa->id_orangtua) {
                $parent = OrangTua::find($siswa->id_orangtua);
                if ($parent) {
                    $parent->updateStatusBasedOnChildren();
                }
            }
        });
    }
}