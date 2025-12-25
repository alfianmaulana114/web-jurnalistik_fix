<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BriefHumas extends Model
{
    use HasFactory;

    protected $table = 'brief_humas';

    protected $fillable = [
        'judul',
        'link_drive',
        'catatan',
    ];
}