<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsType;
use App\Models\NewsGenre;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class NewsService
{
    /**
     * Mengambil daftar berita untuk index Koordinator Jurnalistik.
     *
     * @return array
     */
    public function index(): array
    {
        $news = News::with(['user', 'category', 'type', 'genres', 'approval.user'])->latest()->paginate(10);
        return compact('news');
    }

    /**
     * Data pilihan untuk form pembuatan berita (kategori, tipe, genre).
     *
     * @return array
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
     * Validasi dan simpan berita baru beserta terjemahan.
     *
     * Mengelola pemindahan gambar dari temp ke folder permanen.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateNews($request);

        $imagePath = null;
        if ($request->has('temp_image_id')) {
            $tempImage = TempImage::find($request->temp_image_id);
            if ($tempImage) {
                $newPath = 'images/news/' . $tempImage->filename;
                if (rename(public_path($tempImage->path), public_path($newPath))) {
                    $imagePath = $newPath;
                    $tempImage->delete();
                }
            }
        }

        $news = News::create([
            'title' => $validated['title_id'],
            'slug' => Str::slug($validated['title_id']),
            'content' => $validated['content_id'],
            'meta_description' => null,
            'tags' => $validated['tags_id'],
            'keyword' => $validated['keyword_id'] ?? null,
            'user_id' => auth()->id() ?? 1,
            'news_category_id' => $validated['news_category_id'],
            'news_type_id' => $validated['news_type_id'],
            'image' => $imagePath,
        ]);

        $news->translations()->create([
            'locale' => 'id',
            'title' => $validated['title_id'],
            'content' => $validated['content_id'],
            'meta_description' => null,
            'tags' => $validated['tags_id'] ?? null,
            'keyword' => $validated['keyword_id'] ?? null,
        ]);

        $news->translations()->create([
            'locale' => 'en',
            'title' => $validated['title_en'],
            'content' => $validated['content_en'],
            'meta_description' => null,
            'tags' => $validated['tags_en'] ?? null,
            'keyword' => $validated['keyword_en'] ?? null,
        ]);

        if (!empty($validated['genre_ids'])) {
            $news->genres()->attach($validated['genre_ids']);
        }

        return redirect()->route('koordinator-jurnalistik.news.index')
                         ->with('success', 'Berita berhasil ditambahkan');
    }

    /**
     * Mengambil detail berita untuk ditampilkan.
     *
     * @param int $id
     * @return array
     */
    public function show(int $id): array
    {
        $news = News::with(['user', 'category', 'type', 'genres'])->findOrFail($id);
        return compact('news');
    }

    /**
     * Data untuk form edit berita.
     *
     * @param int $id
     * @return array
     */
    public function edit(int $id): array
    {
        $news = News::with(['genres', 'translations'])->findOrFail($id);
        return [
            'news' => $news,
            'categories' => NewsCategory::all(),
            'types' => NewsType::all(),
            'genres' => NewsGenre::all(),
        ];
    }

    /**
     * Memperbarui berita dan terjemahannya.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $this->validateNews($request, $id);
        $news = News::findOrFail($id);

        $news->update([
            'title' => $validated['title_id'],
            'slug' => Str::slug($validated['title_id']),
            'content' => $validated['content_id'],
            'meta_description' => null,
            'tags' => $validated['tags_id'],
            'keyword' => $validated['keyword_id'] ?? null,
            'news_category_id' => $validated['news_category_id'],
            'news_type_id' => $validated['news_type_id'],
        ]);

        $idTranslation = $news->translations()->where('locale', 'id')->first();
        if ($idTranslation) {
            $idTranslation->update([
                'title' => $validated['title_id'],
                'content' => $validated['content_id'],
                'meta_description' => null,
                'tags' => $validated['tags_id'] ?? null,
                'keyword' => $validated['keyword_id'] ?? null,
            ]);
        } else {
            $news->translations()->create([
                'locale' => 'id',
                'title' => $validated['title_id'],
                'content' => $validated['content_id'],
                'meta_description' => null,
                'tags' => $validated['tags_id'] ?? null,
                'keyword' => $validated['keyword_id'] ?? null,
            ]);
        }

        $enTranslation = $news->translations()->where('locale', 'en')->first();
        if ($enTranslation) {
            $enTranslation->update([
                'title' => $validated['title_en'],
                'content' => $validated['content_en'],
                'meta_description' => null,
                'tags' => $validated['tags_en'] ?? null,
                'keyword' => $validated['keyword_en'] ?? null,
            ]);
        } else {
            $news->translations()->create([
                'locale' => 'en',
                'title' => $validated['title_en'],
                'content' => $validated['content_en'],
                'meta_description' => null,
                'tags' => $validated['tags_en'] ?? null,
                'keyword' => $validated['keyword_en'] ?? null,
            ]);
        }

        if ($request->has('temp_image_id')) {
            $tempImage = TempImage::find($request->temp_image_id);
            if ($tempImage) {
                if ($news->image && file_exists(public_path($news->image))) {
                    unlink(public_path($news->image));
                }
                $newPath = 'images/news/' . $tempImage->filename;
                rename(public_path($tempImage->path), public_path($newPath));
                $news->image = $newPath;
                $news->save();
                $tempImage->delete();
            }
        }

        if (!empty($validated['genre_ids'])) {
            $news->genres()->sync($validated['genre_ids']);
        }

        return redirect()->route('koordinator-jurnalistik.news.index')
                         ->with('success', 'Berita berhasil diperbarui');
    }

    /**
     * Menghapus berita beserta relasi genre dan gambar.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $news = News::findOrFail($id);
        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }
        $news->genres()->detach();
        $news->delete();
        return redirect()->route('koordinator-jurnalistik.news.index')
                         ->with('success', 'Berita berhasil dihapus');
    }

    /**
     * Validasi request berita.
     *
     * @param Request $request
     * @param int|null $id
     * @return array
     */
    private function validateNews(Request $request, $id = null): array
    {
        $rules = [
            'title_id' => 'required|string|max:255',
            'content_id' => 'required|string',
            'tags_id' => 'required|string',
            'keyword_id' => 'nullable|string',
            'title_en' => 'required|string|max:255',
            'content_en' => 'required|string',
            'tags_en' => 'required|string',
            'keyword_en' => 'nullable|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'news_type_id' => 'required|exists:news_types,id',
            'genre_ids' => 'required|array|exists:news_genres,id',
        ];
        return $request->validate($rules);
    }
}


