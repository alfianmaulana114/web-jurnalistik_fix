<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}