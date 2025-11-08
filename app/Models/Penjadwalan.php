<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Penjadwalan
 * 
 * Model ini merepresentasikan jadwal anggota redaksi untuk membuat berita.
 * Setiap jadwal memiliki relasi dengan user (anggota redaksi) dan created_by (koordinator redaksi).
 */
class Penjadwalan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     * 
     * @var string
     */
    protected $table = 'penjadwalan';

    /**
     * Kolom yang dapat diisi secara mass assignment
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tanggal',
        'keterangan',
        'status',
        'created_by',
    ];

    /**
     * Tipe data untuk kolom tertentu
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi dengan User (anggota redaksi yang dijadwalkan)
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi dengan User (koordinator redaksi yang membuat jadwal)
     * 
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tanggal
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTanggal($query, string $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    /**
     * Scope untuk filter berdasarkan bulan dan tahun
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $bulan
     * @param int $tahun
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByBulanTahun($query, int $bulan, int $tahun)
    {
        return $query->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan);
    }

    /**
     * Scope untuk filter berdasarkan status
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan user
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Mendapatkan semua status yang tersedia
     * 
     * @return array
     */
    public static function getAllStatus(): array
    {
        return [
            'pending' => 'Pending',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    }
}
