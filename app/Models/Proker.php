<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_proker',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'catatan',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Status constants
     */
    const STATUS_PLANNING = 'planning';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get all available statuses
     */
    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_PLANNING => 'Perencanaan',
            self::STATUS_ONGOING => 'Sedang Berjalan',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    /**
     * Get the user who created this proker
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all committee members for this proker
     */
    public function panitias(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'proker_panitias')
                    ->withPivot('jabatan_panitia', 'tugas_khusus')
                    ->withTimestamps();
    }

    /**
     * Alias for panitias relationship (singular form)
     */
    public function panitia(): BelongsToMany
    {
        return $this->panitias();
    }

    /**
     * Get designs related to this proker
     */
    public function designs(): HasMany
    {
        return $this->hasMany(Design::class);
    }

    /**
     * Check if proker is active (planning or ongoing)
     */
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_PLANNING, self::STATUS_ONGOING]);
    }

    /**
     * Check if proker is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return self::getAllStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Scope for active prokers
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PLANNING, self::STATUS_ONGOING]);
    }

    /**
     * Scope for completed prokers
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}