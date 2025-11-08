<?php

namespace App\Services\KoordinatorRedaksi;

use App\Models\News;
use App\Models\User;
use App\Models\Brief;
use App\Models\Content;
use App\Models\Design;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Service untuk Dashboard Koordinator Redaksi
 * 
 * Service ini menangani semua logika bisnis terkait dashboard koordinator redaksi,
 * termasuk pengambilan data statistik divisi redaksi, brief dari litbang, caption, dan design.
 * Mengikuti prinsip Single Responsibility dengan fokus pada logika dashboard.
 */
class DashboardService
{
    /**
     * Method helper untuk safe count dengan error handling
     * 
     * Mencegah error jika tabel belum ada atau terjadi masalah database
     * 
     * @param string $modelClass Nama class model
     * @return int Jumlah record atau 0 jika error
     */
    private function safeCount(string $modelClass): int
    {
        try {
            return $modelClass::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Method helper untuk safe query dengan error handling
     * 
     * Mencegah error jika query gagal dengan mengembalikan default value
     * 
     * @param callable $callback Function yang akan dieksekusi
     * @param mixed $default Nilai default jika terjadi error
     * @return mixed Hasil query atau default value
     */
    private function safeQuery(callable $callback, $default = null)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Mengambil semua data yang diperlukan untuk dashboard
     * 
     * Dashboard menampilkan:
     * - Statistik divisi redaksi (koordinator dan anggota)
     * - Brief dari litbang
     * - Caption (content)
     * - Design media
     * - Berita terkait divisi redaksi
     * 
     * @return array Array berisi semua data untuk dashboard
     */
    public function getDashboardData(): array
    {
        // Statistik divisi redaksi
        $redaksiStats = $this->getRedaksiStats();
        
        // Statistik brief dari litbang
        $briefStats = $this->getBriefStats();
        
        // Statistik caption (content)
        $captionStats = $this->getCaptionStats();
        
        // Statistik design
        $designStats = $this->getDesignStats();
        
        // Statistik berita
        $newsStats = $this->getNewsStats();
        
        // Data terbaru untuk ditampilkan
        $recentData = $this->getRecentData();
        
        // Data chart untuk visualisasi
        $chartData = $this->getChartData();

        return array_merge(
            $redaksiStats,
            $briefStats,
            $captionStats,
            $designStats,
            $newsStats,
            $recentData,
            $chartData
        );
    }

    /**
     * Mengambil statistik divisi redaksi
     * 
     * @return array Statistik koordinator dan anggota redaksi
     */
    private function getRedaksiStats(): array
    {
        return [
            'redaksi_coordinators' => User::where('role', User::ROLE_KOORDINATOR_REDAKSI)->count(),
            'redaksi_members' => User::where('role', User::ROLE_ANGGOTA_REDAKSI)->count(),
            'redaksi_total' => User::whereIn('role', [
                User::ROLE_KOORDINATOR_REDAKSI,
                User::ROLE_ANGGOTA_REDAKSI
            ])->count(),
        ];
    }

    /**
     * Mengambil statistik brief dari litbang
     * 
     * @return array Statistik brief
     */
    private function getBriefStats(): array
    {
        $totalBriefs = $this->safeCount(Brief::class);
        $urgentBriefs = $this->safeQuery(function () {
            return Brief::where('status', 'urgent')->count();
        }, 0);
        $pendingBriefs = $this->safeQuery(function () {
            return Brief::where('status', 'pending')->count();
        }, 0);
        $completedBriefs = $this->safeQuery(function () {
            return Brief::where('status', 'completed')->count();
        }, 0);

        return [
            'brief_total' => $totalBriefs,
            'brief_urgent' => $urgentBriefs,
            'brief_pending' => $pendingBriefs,
            'brief_completed' => $completedBriefs,
        ];
    }

    /**
     * Mengambil statistik caption (content)
     * 
     * @return array Statistik caption
     */
    private function getCaptionStats(): array
    {
        $totalCaptions = $this->safeCount(Content::class);
        $captionBerita = $this->safeQuery(function () {
            return Content::where('jenis_konten', 'caption_berita')->count();
        }, 0);
        $captionDesain = $this->safeQuery(function () {
            return Content::where('jenis_konten', 'caption_desain')->count();
        }, 0);
        $captionMediaKreatif = $this->safeQuery(function () {
            return Content::where('jenis_konten', 'caption_media_kreatif')->count();
        }, 0);

        return [
            'caption_total' => $totalCaptions,
            'caption_berita' => $captionBerita,
            'caption_desain' => $captionDesain,
            'caption_media_kreatif' => $captionMediaKreatif,
        ];
    }

    /**
     * Mengambil statistik design
     * 
     * @return array Statistik design
     */
    private function getDesignStats(): array
    {
        $totalDesigns = $this->safeCount(Design::class);
        $designPublished = $this->safeQuery(function () {
            return Design::where('status', 'published')->count();
        }, 0);
        $designDraft = $this->safeQuery(function () {
            return Design::where('status', 'draft')->count();
        }, 0);

        return [
            'design_total' => $totalDesigns,
            'design_published' => $designPublished,
            'design_draft' => $designDraft,
        ];
    }

    /**
     * Mengambil statistik berita
     * 
     * @return array Statistik berita
     */
    private function getNewsStats(): array
    {
        $totalNews = $this->safeCount(News::class);
        $newsPublished = $this->safeQuery(function () {
            return News::where('status', 'published')->count();
        }, 0);
        $newsDraft = $this->safeQuery(function () {
            return News::where('status', 'draft')->count();
        }, 0);
        $totalViews = $this->safeQuery(function () {
            return News::sum('views');
        }, 0);

        return [
            'news_total' => $totalNews,
            'news_published' => $newsPublished,
            'news_draft' => $newsDraft,
            'news_total_views' => $totalViews,
        ];
    }

    /**
     * Mengambil data terbaru untuk ditampilkan di dashboard
     * 
     * @return array Data terbaru (brief, caption, design, news)
     */
    private function getRecentData(): array
    {
        $recentBriefs = $this->safeQuery(function () {
            return Brief::with('creator')->latest()->take(5)->get();
        }, collect());

        $recentCaptions = $this->safeQuery(function () {
            return Content::with('berita', 'desain')->latest()->take(5)->get();
        }, collect());

        $recentDesigns = $this->safeQuery(function () {
            return Design::with('berita')->latest()->take(5)->get();
        }, collect());

        $recentNews = $this->safeQuery(function () {
            return News::with('user')->latest()->take(5)->get();
        }, collect());

        return [
            'recent_briefs' => $recentBriefs,
            'recent_captions' => $recentCaptions,
            'recent_designs' => $recentDesigns,
            'recent_news' => $recentNews,
        ];
    }

    /**
     * Mengambil data untuk chart visualisasi
     * 
     * @return array Data chart (monthly news, monthly briefs, dll)
     */
    private function getChartData(): array
    {
        $currentYear = Carbon::now()->year;

        // Data bulanan untuk berita
        $monthlyNews = $this->safeQuery(function () use ($currentYear) {
            return News::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }, []);

        // Data bulanan untuk brief
        $monthlyBriefs = $this->safeQuery(function () use ($currentYear) {
            return Brief::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }, []);

        // Data bulanan untuk caption
        $monthlyCaptions = $this->safeQuery(function () use ($currentYear) {
            return Content::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }, []);

        // Data bulanan untuk design
        $monthlyDesigns = $this->safeQuery(function () use ($currentYear) {
            return Design::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }, []);

        $months = range(1, 12);
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        $newsData = array_map(fn($m) => $monthlyNews[$m] ?? 0, $months);
        $briefData = array_map(fn($m) => $monthlyBriefs[$m] ?? 0, $months);
        $captionData = array_map(fn($m) => $monthlyCaptions[$m] ?? 0, $months);
        $designData = array_map(fn($m) => $monthlyDesigns[$m] ?? 0, $months);

        return [
            'monthly_labels' => $monthlyLabels,
            'monthly_news_data' => $newsData,
            'monthly_brief_data' => $briefData,
            'monthly_caption_data' => $captionData,
            'monthly_design_data' => $designData,
        ];
    }
}

