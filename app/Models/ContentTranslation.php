<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentTranslation extends Model
{
    protected $fillable = [
        'content_id',
        'locale',
        'judul',
        'caption',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}