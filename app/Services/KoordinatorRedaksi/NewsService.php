<?php

namespace App\Services\KoordinatorRedaksi;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsType;
use App\Models\NewsGenre;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

/**
 * Service untuk Manajemen Berita Koordinator Redaksi
 * 
 * Service ini menangani semua logika bisnis terkait manajemen berita untuk koordinator redaksi.
 * Mengikuti prinsip Single Responsibility dengan fokus pada operasi CRUD berita.
 */
class NewsService
{
    /**
     * Menampilkan daftar semua berita dengan pagination
     * 
     * @return array Data berita dengan relasi user, category, type, dan genres
     */
    public function index(): array
    {
        $news = News::with(['user', 'category', 'type', 'genres'])
            ->latest()
            ->paginate(10);
        
        return compact('news');
    }

    /**
     * Mengambil data yang diperlukan untuk form create berita
     * 
     * @return array Data categories, types, dan genres
     */
    public function create(): array
    {
        return [
            'categories' => NewsCategory::all(),
            'types' => NewsType::all(),
            'genres' => NewsGenre::all(),
        ];
    }

    /**
     * Menyimpan berita baru ke database
     * 
     * @param Request $request Request dari form
     * @return RedirectResponse Redirect ke halaman index dengan pesan sukses
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateNews($request);

        // Handle image upload dari temp image
        $imagePath = null;
        if ($request->has('temp_image_id')) {
            $imagePath = $this->handleImageUpload($request->temp_image_id);
        }

        // Create news record
        $news = News::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'meta_description' => $validated['meta_description'],
            'tags' => $validated['tags'],
            'keyword' => $validated['keyword'] ?? null,
            'user_id' => auth()->id(),
            'news_category_id' => $validated['news_category_id'],
            'news_type_id' => $validated['news_type_id'],
            'image' => $imagePath,
        ]);

        // Attach genres jika ada
        if (!empty($validated['genre_ids'])) {
            $news->genres()->attach($validated['genre_ids']);
        }

        return redirect()->route('koordinator-redaksi.news.index')
            ->with('success', 'Berita berhasil ditambahkan');
    }

    /**
     * Menampilkan detail berita
     * 
     * @param int $id ID berita
     * @return array Data berita dengan relasi
     */
    public function show(int $id): array
    {
        $news = News::with(['user', 'category', 'type', 'genres'])
            ->findOrFail($id);
        
        return compact('news');
    }

    /**
     * Mengambil data yang diperlukan untuk form edit berita
     * 
     * @param int $id ID berita
     * @return array Data berita, categories, types, dan genres
     */
    public function edit(int $id): array
    {
        $news = News::with('genres')->findOrFail($id);
        
        return [
            'news' => $news,
            'categories' => NewsCategory::all(),
            'types' => NewsType::all(),
            'genres' => NewsGenre::all(),
        ];
    }

    /**
     * Memperbarui berita yang sudah ada
     * 
     * @param Request $request Request dari form
     * @param int $id ID berita
     * @return RedirectResponse Redirect ke halaman index dengan pesan sukses
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $this->validateNews($request, $id);
        $news = News::findOrFail($id);

        // Update news data
        $news->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'meta_description' => $validated['meta_description'],
            'tags' => $validated['tags'],
            'keyword' => $validated['keyword'] ?? null,
            'news_category_id' => $validated['news_category_id'],
            'news_type_id' => $validated['news_type_id'],
        ]);

        // Handle image update jika ada temp_image_id
        if ($request->has('temp_image_id')) {
            $this->handleImageUpdate($news, $request->temp_image_id);
        }

        // Sync genres
        if (!empty($validated['genre_ids'])) {
            $news->genres()->sync($validated['genre_ids']);
        }

        return redirect()->route('koordinator-redaksi.news.index')
            ->with('success', 'Berita berhasil diperbarui');
    }

    /**
     * Menghapus berita dari database
     * 
     * @param int $id ID berita
     * @return RedirectResponse Redirect ke halaman index dengan pesan sukses
     */
    public function destroy(int $id): RedirectResponse
    {
        $news = News::findOrFail($id);
        
        // Hapus image jika ada
        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }
        
        // Detach genres
        $news->genres()->detach();
        
        // Delete news
        $news->delete();
        
        return redirect()->route('koordinator-redaksi.news.index')
            ->with('success', 'Berita berhasil dihapus');
    }

    /**
     * Validasi data berita dari request
     * 
     * @param Request $request Request dari form
     * @param int|null $id ID berita (untuk update, null untuk create)
     * @return array Data yang sudah divalidasi
     */
    private function validateNews(Request $request, ?int $id = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_description' => 'required|string|max:160',
            'tags' => 'required|string',
            'keyword' => 'nullable|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'news_type_id' => 'required|exists:news_types,id',
            'genre_ids' => 'required|array|exists:news_genres,id',
        ];
        
        return $request->validate($rules);
    }

    /**
     * Handle upload image dari temp image
     * 
     * @param int $tempImageId ID temp image
     * @return string|null Path image atau null jika gagal
     */
    private function handleImageUpload(int $tempImageId): ?string
    {
        $tempImage = TempImage::find($tempImageId);
        
        if (!$tempImage) {
            return null;
        }

        $newPath = 'images/news/' . $tempImage->filename;
        
        if (rename(public_path($tempImage->path), public_path($newPath))) {
            $tempImage->delete();
            return $newPath;
        }

        return null;
    }

    /**
     * Handle update image dari temp image
     * 
     * @param News $news Model berita
     * @param int $tempImageId ID temp image
     * @return void
     */
    private function handleImageUpdate(News $news, int $tempImageId): void
    {
        $tempImage = TempImage::find($tempImageId);
        
        if (!$tempImage) {
            return;
        }

        // Hapus image lama jika ada
        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }

        // Pindahkan temp image ke lokasi final
        $newPath = 'images/news/' . $tempImage->filename;
        
        if (rename(public_path($tempImage->path), public_path($newPath))) {
            $news->image = $newPath;
            $news->save();
            $tempImage->delete();
        }
    }
}

