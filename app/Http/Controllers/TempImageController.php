<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TempImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                $file->move(public_path('images/temp'), $filename);
                
                $tempImage = TempImage::create([
                    'filename' => $filename,
                    'path' => 'images/temp/' . $filename,
                    'original_name' => $file->getClientOriginalName()
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'image_id' => $tempImage->id,
                    'path' => asset($tempImage->path)
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengunggah gambar. Silakan coba lagi.'
                ], 500);
            }
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'Tidak ada file yang diunggah'
        ], 400);
    }

    // ... existing code ...
public function crop(Request $request)
{
    $validator = Validator::make($request->all(), [
        'image' => 'required',  // Hapus validasi mime karena ini adalah blob dari canvas
        'temp_image_id' => 'required|exists:temp_images,id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first()
        ], 422);
    }

    try {
        $tempImage = TempImage::findOrFail($request->temp_image_id);
        
        // Decode base64 image
        $imageData = $request->get('image');
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageBinary = base64_decode($imageData);
        
        // Generate filename
        $filename = time() . '_' . Str::random(10) . '.jpg';
        $path = public_path('images/temp/' . $filename);
        
        // Save image
        file_put_contents($path, $imageBinary);
        
        // Delete old temp image if exists
        if (file_exists(public_path($tempImage->path))) {
            unlink(public_path($tempImage->path));
        }
        
        // Update temp image record
        $tempImage->update([
            'filename' => $filename,
            'path' => 'images/temp/' . $filename,
            'original_name' => 'cropped_' . $tempImage->original_name
        ]);
        
        return response()->json([
            'status' => 'success',
            'image_id' => $tempImage->id,
            'path' => asset($tempImage->path)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal menyimpan gambar yang sudah dipotong. Silakan coba lagi.'
        ], 500);
    }
}
// ... existing code ...
}