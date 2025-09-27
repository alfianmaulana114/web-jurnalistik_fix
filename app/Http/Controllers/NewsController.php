<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function dashboard(): View
    {
        $news = $this->news->latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function index(): View
    {
        $latestNews = $this->news->latest()->first();
        
        // Debug info
        if ($latestNews) {
            \Log::info('Image path:', [
                'image' => $latestNews->image,
                'full_path' => public_path('images/news/' . $latestNews->image)
            ]);
        }

        return view('home', [
            'latestNews' => $latestNews,
            'recentNews' => $this->news->latest()->skip(1)->take(3)->get(),
            'mediaPartnerNews' => $this->news->where('subcategory', 'Media Partner')->latest()->take(6)->get(),
            'sidebarNews' => $this->news->latest()->take(4)->get(),
            'allNews' => $this->news->latest()->take(5)->get() // Menambahkan 5 berita terbaru
        ]);
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'subcategory' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $news = new News();
        $news->title = $request->title;
        $news->slug = Str::slug($request->title);
        $news->category = $request->category;
        $news->subcategory = $request->subcategory;
        $news->content = $request->content;
        $news->user_id = 1; // Set user_id default ke 1 untuk sementara

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/news'), $filename);
            $news->image = 'images/news/' . $filename;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan');
    }

    public function edit($id): View
    {
        $news = $this->news->findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'subcategory' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $news = $this->news->findOrFail($id);
        $news->title = $request->title;
        $news->slug = Str::slug($request->title);
        $news->category = $request->category;
        $news->subcategory = $request->subcategory;
        $news->content = $request->content;

        if ($request->hasFile('image')) {
            if ($news->image && file_exists(public_path($news->image))) {
                unlink(public_path($news->image));
            }
            
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/news'), $filename);
            $news->image = 'images/news/' . $filename;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy($id)
    {
        $news = $this->news->findOrFail($id);
        
        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }
        
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus');
    }
}