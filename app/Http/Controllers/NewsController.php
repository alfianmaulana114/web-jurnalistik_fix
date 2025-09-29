<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsType;
use App\Models\NewsGenre;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\NewsImageService;
use App\Services\EditorService;
use App\Services\ImageCropperService;
use Illuminate\Http\JsonResponse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class NewsController extends Controller
{
    private $news;
    private $category;
    private $type;
    private $genre;
    private $imageService;
    private $editorService;
    private $cropperService;
    private $manager;

    public function __construct(
        News $news,
        NewsCategory $category,
        NewsType $type,
        NewsGenre $genre,
        NewsImageService $imageService,
        EditorService $editorService,
        ImageCropperService $cropperService
    ) {
        $this->news = $news;
        $this->category = $category;
        $this->type = $type;
        $this->genre = $genre;
        $this->imageService = $imageService;
        $this->editorService = $editorService;
        $this->cropperService = $cropperService;
        $this->manager = new ImageManager(new Driver());
    }

    public function index(): View
    {
        $news = $this->news->with(['user', 'category', 'type', 'genres'])
                          ->latest()
                          ->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create(): View
    {
        $categories = $this->category->all();
        $types = $this->type->all();
        $genres = $this->genre->all();
        
        // Tambahkan debugging
        \Log::info('Categories:', $categories->toArray());
        \Log::info('Types:', $types->toArray());
        \Log::info('Genres:', $genres->toArray());
        
        return view('admin.news.create', [
            'categories' => $categories,
            'types' => $types,
            'genres' => $genres,
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts()
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateNews($request);
        
        // Inisialisasi image dengan null
        $imagePath = null;
        
        // Cek jika ada temp_image_id
        if ($request->has('temp_image_id')) {
            $tempImage = TempImage::find($request->temp_image_id);
            if ($tempImage) {
                // Pindahkan gambar dari folder temp ke folder news
                $newPath = 'images/news/' . $tempImage->filename;
                if (rename(public_path($tempImage->path), public_path($newPath))) {
                    $imagePath = $newPath;
                    // Hapus record temp image
                    $tempImage->delete();
                }
            }
        }
        
        $news = $this->news->create([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['title']),
            'content' => $validatedData['content'],
            'meta_description' => $validatedData['meta_description'],
            'tags' => $validatedData['tags'],
            'keyword' => $validatedData['keyword'],
            'user_id' => 1,
            'news_category_id' => $validatedData['news_category_id'],
            'news_type_id' => $validatedData['news_type_id'],
            'image' => $imagePath // Set path gambar yang sudah dipindahkan
        ]);
    
        if (!empty($validatedData['genre_ids'])) {
            $news->genres()->attach($validatedData['genre_ids']);
        }
    
        return redirect()->route('admin.news.index')
                        ->with('success', 'Berita berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateNews($request, $id);
        $news = $this->news->findOrFail($id);
    
        $news->update([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['title']),
            'content' => $validatedData['content'],
            'meta_description' => $validatedData['meta_description'],
            'tags' => $validatedData['tags'],
            'keyword' => $validatedData['keyword'],
            'news_category_id' => $validatedData['news_category_id'],
            'news_type_id' => $validatedData['news_type_id']
        ]);
    
        // Handle image from temp
        if ($request->has('temp_image_id')) {
            $tempImage = TempImage::find($request->temp_image_id);
            if ($tempImage) {
                // Delete old image if exists
                if ($news->image && file_exists(public_path($news->image))) {
                    unlink(public_path($news->image));
                }
                
                // Move image from temp to permanent location
                $newPath = 'images/news/' . $tempImage->filename; // Filename sudah dalam format .webp
                rename(public_path($tempImage->path), public_path($newPath));
                
                $news->image = $newPath;
                $news->save();
                
                // Delete temp record
                $tempImage->delete();
            }
        } else if ($request->hasFile('image')) {
            // Fallback to old method if no temp_image_id
            if ($news->image) {
                $this->imageService->delete($news->image);
            }
            
            // Konversi ke WebP menggunakan Intervention Image v3
            $image = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.webp';
            
            $img = $this->manager->read($image->getRealPath());
            $img->toWebp(80)->save(public_path('images/news/' . $filename));
            
            $news->image = 'images/news/' . $filename;
            $news->save();
        }
    
        $news->genres()->sync($validatedData['genre_ids']);
    
        return redirect()->route('admin.news.index')
                        ->with('success', 'Berita berhasil diperbarui');
    }

    private function validateNews(Request $request, $id = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_description' => 'required|string|max:160',
            'tags' => 'required|string',
            'keyword' => 'nullable|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'news_type_id' => 'required|exists:news_types,id',
            'genre_ids' => 'required|array|exists:news_genres,id'
        ];
    
        return $request->validate($rules);
    }

    // Hapus method uploadImage karena tidak digunakan lagi
    public function destroy($id)
    {
        $news = $this->news->findOrFail($id);
        
        if ($news->image) {
            $this->imageService->delete($news->image);
        }
        
        $news->genres()->detach();
        $news->delete();

        return redirect()->route('admin.news.index')
                        ->with('success', 'Berita berhasil dihapus');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $path = $this->imageService->store($request->file('image'));
            return response()->json(asset($path));
        }
        return response()->json('Upload failed', 400);
    }

    public function edit($id): View
    {
        $news = $this->news->with('genres')->findOrFail($id);
        $categories = $this->category->all();
        $types = $this->type->all();
        $genres = $this->genre->all();
        
        return view('admin.news.edit', [
            'news' => $news,
            'categories' => $categories,
            'types' => $types,
            'genres' => $genres,
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts()
        ]);
    }
}