<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'meta_description',
        'tags',
        'keyword',
        'views',
        'user_id',
        'news_category_id',
        'news_type_id'
    ];

    protected $appends = ['image_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(NewsType::class, 'news_type_id');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(NewsGenre::class, 'news_genre_pivot', 'news_id', 'news_genre_id')
                    ->withTimestamps();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(NewsTranslation::class);
    }

    public function caption(): HasOne
    {
        return $this->hasOne(Content::class, 'berita_id')->where('jenis_konten', Content::TYPE_CAPTION_BERITA);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(NewsApproval::class);
    }

    public function approval(): HasOne
    {
        return $this->hasOne(NewsApproval::class);
    }

    public function scopeApproved($query)
    {
        return $query->whereHas('approvals');
    }

    /**
     * Get the image URL with proper path
     * Path yang benar: public/images/news/
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('images/no-image.jpg');
        }

        $raw = trim((string) $this->image);
        $path = str_replace('\\', '/', $raw);
        $path = ltrim($path, '/');

        // Jika absolute path (Windows drive letter atau mengandung ':/'), ambil filename saja
        if (preg_match('/^[A-Za-z]:\//', $path) || str_contains($path, ':/')) {
            $filename = basename($path);
            $path = 'images/news/' . $filename;
        }

        // Mapping otomatis dari temp ke news
        if (str_starts_with($path, 'images/temp/')) {
            $path = 'images/news/' . basename($path);
        }

        // Jika hanya filename
        if (!str_contains($path, '/')) {
            $path = 'images/news/' . $path;
        }

        // Jika diawali public/, hilangkan
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        // Normalisasi akhir ke images/news jika bukan path yang valid
        if (!str_starts_with($path, 'images/news/') && !str_starts_with($path, 'images/')) {
            $path = 'images/news/' . basename($path);
        }

        // Fallback jika file tidak ditemukan
        $publicFile = public_path($path);
        if (!file_exists($publicFile)) {
            return asset('images/no-image.jpg');
        }

        return asset($path);
    }
}