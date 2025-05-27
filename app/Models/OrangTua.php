<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrangTua extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'aktif';
    const STATUS_INACTIVE = 'nonaktif';
    const STATUS_PENDING = 'pending';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orangtua';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_orangtua';

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
        'id_user',
        'nama_lengkap',
        'alamat',
        'nomor_telepon',
        'pekerjaan',
        'status',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    /**
     * Get the user associated with the parent.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the children (students) for the parent.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_orangtua', 'id_orangtua');
    }
    
    /**
     * Get permission letters associated with this parent.
     */
    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_orangtua', 'id_orangtua');
    }

    /**
     * Update status based on children's status
     * 
     * @return void
     */
    public function updateStatusBasedOnChildren()
    {
        // Get all children of this parent (fresh from database)
        $children = $this->siswa()->get();
        
        // If no children, status should be pending
        if ($children->isEmpty()) {
            $newStatus = self::STATUS_PENDING;
        } else {
            // Check if all children are inactive
            $allInactive = $children->every(function ($child) {
                return $child->status === Siswa::STATUS_INACTIVE;
            });
            
            if ($allInactive) {
                $newStatus = self::STATUS_INACTIVE;
            } else {
                // If at least one child is active, parent should be active
                $newStatus = self::STATUS_ACTIVE;
            }
        }
        
        // Only update if status has changed to avoid unnecessary saves
        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
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
            case self::STATUS_PENDING:
                return '<span class="badge bg-warning text-dark">Pending</span>';
            default:
                return '<span class="badge bg-light text-dark">Unknown</span>';
        }
    }
    
    /**
     * Override the save method to handle status updates
     */
    public function save(array $options = [])
    {
        // Set default values for audit fields if creating new record
        if (!$this->exists) {
            $this->dibuat_pada = $this->dibuat_pada ?: now();
            $this->dibuat_oleh = $this->dibuat_oleh ?: (Auth::user() ? Auth::user()->username : 'system');
        }
        
        // Always update modified fields
        $this->diperbarui_pada = now();
        $this->diperbarui_oleh = Auth::user() ? Auth::user()->username : 'system';
        
        return parent::save($options);
    }
    
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // When a parent is deleted, make sure to handle orphaned children
        static::deleting(function ($orangTua) {
            // You might want to handle this differently based on your business logic
            // For example, you could set children's id_orangtua to null
            $orangTua->siswa()->update(['id_orangtua' => null]);
        });
    }
}