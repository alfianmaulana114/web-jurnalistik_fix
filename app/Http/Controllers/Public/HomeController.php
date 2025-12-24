<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Model News untuk query data berita publik.
     */
    private $news;

    /**
     * Inisialisasi dependency model News.
     */
    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * Menampilkan halaman beranda publik.
     *
     * Memuat headline, daftar berita terbaru, dan bagian media partner.
     * Hanya berita yang sudah disetujui dan memiliki caption yang ditampilkan.
     *
     * @return View
     */
    public function index(): View
    {
        // Query base untuk berita yang sudah approved dan punya caption
        $baseQuery = $this->news->with(['category', 'type', 'genres'])
                                ->whereHas('caption')
                                ->approved()
                                ->latest();

        // Berita terbaru untuk headline
        $latestNews = (clone $baseQuery)->first();

        // Berita terbaru untuk grid 3 kolom (skip 1 jika ada latestNews)
        $recentNewsQuery = clone $baseQuery;
        if ($latestNews) {
            $recentNewsQuery->where('id', '!=', $latestNews->id);
        }
        $recentNews = $recentNewsQuery->take(3)->get();

        // Berita untuk media partner
        $mediaPartnerNews = (clone $baseQuery)
                                      ->where('news_type_id', 2)
                                      ->take(3)
                                      ->get();

        // Semua berita untuk bagian "Seluruh Berita"
        $allNews = (clone $baseQuery)->paginate(5);

        return view('public.home', compact('latestNews', 'recentNews', 'mediaPartnerNews', 'allNews'));
    }
}