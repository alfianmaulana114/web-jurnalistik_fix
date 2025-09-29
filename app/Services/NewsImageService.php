<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NewsImageService
{
    public function store(UploadedFile $image): string
    {
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/news'), $filename);
        return 'images/news/' . $filename;
    }

    public function delete(?string $path): bool
    {
        if ($path && file_exists(public_path($path))) {
            return unlink(public_path($path));
        }
        return false;
    }
}