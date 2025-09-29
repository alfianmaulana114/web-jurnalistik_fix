<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class TempImageController extends Controller
{
    private $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:2048',  // Max 2MB
                'original_name' => 'required|string'
            ]);
            
            if (!$request->hasFile('image')) {
                throw new \Exception('Tidak ada file gambar yang dikirim.');
            }
            
            $image = $request->file('image');
            if (!$image->isValid()) {
                throw new \Exception('File gambar tidak valid.');
            }
            
            $filename = time() . '_' . Str::random(10) . '.webp';
            
            $path = public_path('images/temp');
            if (!file_exists($path)) {
                if (!mkdir($path, 0775, true)) {
                    throw new \Exception('Gagal membuat direktori temp.');
                }
            }
            
            try {
                // Konversi ke WebP menggunakan Intervention Image v3
                $img = $this->manager->read($image->getRealPath());
                $img->toWebp(80)->save(public_path('images/temp/' . $filename));
            } catch (\Exception $e) {
                Log::error('Image processing error: ' . $e->getMessage());
                throw new \Exception('Gagal memproses gambar: ' . $e->getMessage());
            }
            
            $tempImage = TempImage::create([
                'filename' => $filename,
                'path' => 'images/temp/' . $filename,
                'original_name' => $request->original_name
            ]);
            
            return response()->json([
                'status' => 'success',
                'image_id' => $tempImage->id,
                'path' => asset($tempImage->path)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            Log::error('Image upload error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan gambar: ' . $e->getMessage()
            ], 500);
        }
    }
}