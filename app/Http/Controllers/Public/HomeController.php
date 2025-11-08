<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function index(): View
    {
        // Berita terbaru untuk headline
        $latestNews = $this->news->with(['category', 'type', 'genres'])
                                ->latest()
                                ->first();

        // Berita terbaru untuk grid 3 kolom
        $recentNews = $this->news->with(['category', 'type', 'genres'])
                                ->latest()
                                ->skip(1)  // Skip berita headline
                                ->take(3)
                                ->get();

        // Berita untuk media partner
        $mediaPartnerNews = $this->news->with(['category', 'type', 'genres'])
                                      ->where('news_type_id', 2) // Sesuaikan dengan ID tipe berita media partner
                                      ->latest()
                                      ->take(3)
                                      ->get();

        // Semua berita untuk bagian "Seluruh Berita"
        $allNews = $this->news->with(['category', 'type', 'genres'])
                             ->latest()
                             ->paginate(5);

        return view('home', compact('latestNews', 'recentNews', 'mediaPartnerNews', 'allNews'));
    }
}