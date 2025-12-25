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

        return [
            'brief_total' => $totalBriefs,
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

        return [
            'caption_total' => $totalCaptions,
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

        return [
            'design_total' => $totalDesigns,
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
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // News sudah terbit bulan ini = news yang sudah approved DAN punya caption (tampil di home) yang dibuat di bulan ini
        $newsSudahTerbit = $this->safeQuery(function () use ($currentMonth, $currentYear) {
            return News::has('approval')
                ->whereHas('caption')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count();
        }, 0);
        
        // News belum terbit bulan ini = news yang belum approved ATAU belum punya caption yang dibuat di bulan ini
        $newsBelumTerbit = $this->safeQuery(function () use ($currentMonth, $currentYear) {
            return News::where(function($query) {
                $query->doesntHave('approval')
                      ->orDoesntHave('caption');
            })
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        }, 0);
        
        // Total views bulan ini = total views dari berita yang dibuat di bulan ini
        $totalViews = $this->safeQuery(function () use ($currentMonth, $currentYear) {
            return News::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('views') ?? 0;
        }, 0);

        return [
            'news_total' => $totalNews,
            'news_sudah_terbit' => $newsSudahTerbit,
            'news_belum_terbit' => $newsBelumTerbit,
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
            return Brief::with('contents')->latest()->take(5)->get();
        }, collect());

        $recentCaptions = $this->safeQuery(function () {
            return Content::with('berita', 'desain', 'creator')->latest()->take(5)->get();
        }, collect());

        $recentDesigns = $this->safeQuery(function () {
            return Design::with('berita', 'creator')->latest()->take(5)->get();
        }, collect());

        $recentNews = $this->safeQuery(function () {
            return News::with('user', 'approval', 'caption')->latest()->take(5)->get();
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
     * @return array Data chart (monthly views)
     */
    private function getChartData(): array
    {
        $currentYear = Carbon::now()->year;

        // Data bulanan untuk views (total views dari berita yang dibuat per bulan)
        $monthlyViews = $this->safeQuery(function () use ($currentYear) {
            return News::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(views) as total_views'))
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total_views', 'month')
                ->toArray();
        }, []);

        $months = range(1, 12);
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        $viewsData = array_map(fn($m) => (int)($monthlyViews[$m] ?? 0), $months);

        return [
            'monthly_labels' => $monthlyLabels,
            'monthly_views_data' => $viewsData,
        ];
    }
}

