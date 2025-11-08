<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk tabel funfacts
 * 
 * Menyimpan data funfact yang berisi judul, isi, dan link referensi
 */
class Funfact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'isi',
        'link_referensi',
        'created_by',
    ];

    /**
     * Relasi ke User (pembuat funfact)
     * 
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get formatted link referensi as array
     * 
     * @return array
     */
    public function getLinksArray(): array
    {
        if (empty($this->link_referensi)) {
            return [];
        }

        // Split by newline and filter empty lines
        $links = array_filter(
            array_map('trim', explode("\n", $this->link_referensi)),
            function($link) {
                return !empty($link);
            }
        );

        return array_values($links);
    }
}

