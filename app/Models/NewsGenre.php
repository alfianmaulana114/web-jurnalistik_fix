<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsGenre extends Model
{
    protected $fillable = ['name'];

    public function news(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'news_genre_pivot', 'news_genre_id', 'news_id')
                    ->withTimestamps();
    }
}