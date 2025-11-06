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
    public function index(): array
    {
        $news = News::with(['user', 'category', 'type', 'genres'])->latest()->paginate(10);
        return compact('news');
    }

    public function create(): array
    {
        return [
            'categories' => NewsCategory::all(),
            'types' => NewsType::all(),
            'genres' => NewsGenre::all(),
        ];
    }

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
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'meta_description' => $validated['meta_description'],
            'tags' => $validated['tags'],
            'keyword' => $validated['keyword'],
            'user_id' => auth()->id() ?? 1,
            'news_category_id' => $validated['news_category_id'],
            'news_type_id' => $validated['news_type_id'],
            'image' => $imagePath,
        ]);

        if (!empty($validated['genre_ids'])) {
            $news->genres()->attach($validated['genre_ids']);
        }

        return redirect()->route('koordinator-jurnalistik.news.index')
                         ->with('success', 'Berita berhasil ditambahkan');
    }

    public function show(int $id): array
    {
        $news = News::with(['user', 'category', 'type', 'genres'])->findOrFail($id);
        return compact('news');
    }

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

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $this->validateNews($request, $id);
        $news = News::findOrFail($id);

        $news->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'meta_description' => $validated['meta_description'],
            'tags' => $validated['tags'],
            'keyword' => $validated['keyword'],
            'news_category_id' => $validated['news_category_id'],
            'news_type_id' => $validated['news_type_id'],
        ]);

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
            'genre_ids' => 'required|array|exists:news_genres,id',
        ];
        return $request->validate($rules);
    }
}


