<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'news_id',
        'name',
        'email',
        'content',
    ];

    /**
     * Get the news that owns the comment
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}