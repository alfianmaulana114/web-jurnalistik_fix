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

    /**
     * Menyimpan gambar sementara (temp) dan mengonversi ke format WebP.
     *
     * Melakukan validasi, pemrosesan gambar, dan menyimpan metadata
     * ke tabel `temp_images`. Mengembalikan JSON berisi `image_id` dan `path`.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:2048|mimes:jpeg,jpg,png,gif,webp',  // Max 2MB, specific mime types
                'original_name' => 'required|string|max:255'
            ]);
            
            if (!$request->hasFile('image')) {
                throw new \Exception('Tidak ada file gambar yang dikirim.');
            }
            
            $image = $request->file('image');
            if (!$image->isValid()) {
                throw new \Exception('File gambar tidak valid.');
            }
            
            // Generate secure filename - prevent path traversal
            $filename = time() . '_' . Str::random(10) . '.webp';
            
            // Sanitize original_name to prevent XSS and path traversal
            $originalName = basename(strip_tags($request->original_name));
            $originalName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $originalName = substr($originalName, 0, 255); // Limit length
            
            $path = public_path('images/temp');
            if (!file_exists($path)) {
                if (!mkdir($path, 0755, true)) { // More secure permissions (0755 instead of 0775)
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
                'original_name' => $originalName // Sanitized original name
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

    /**
     * Menghapus gambar sementara (temp).
     * 
     * Hanya user yang terautentikasi yang bisa menghapus temp image.
     * Memastikan file dihapus dari filesystem dan record dihapus dari database.
     *
     * @param TempImage $tempImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TempImage $tempImage)
    {
        try {
            // Hapus file dari filesystem jika ada
            $filePath = public_path($tempImage->path);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            
            // Hapus record dari database
            $tempImage->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Gambar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Temp image delete error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus gambar: ' . $e->getMessage()
            ], 500);
        }
    }
}