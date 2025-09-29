<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsType extends Model
{
    protected $fillable = ['name'];

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'news_type_id');
    }
}