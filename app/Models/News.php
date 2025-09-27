<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'subcategory',
        'content',
        'image',
    ];

    /**
     * Get the user that owns the news.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}