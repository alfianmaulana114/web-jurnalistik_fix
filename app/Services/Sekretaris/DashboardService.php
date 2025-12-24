<?php

namespace App\Services\Sekretaris;

use App\Models\User;
use App\Models\Proker;
use App\Models\News;
use App\Models\Notulensi;
use App\Models\Absen;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardData(): array
    {
        // Statistik umum
        $stats = $this->getStats();
        
        // Statistik per divisi
        $divisiStats = $this->getDivisiStats();
        
        // Statistik proker
        $prokerStats = $this->getProkerStats();
        
        // Data bulanan untuk chart
        $monthlyData = $this->getMonthlyData();
        
        // Aktivitas terbaru
        $recentActivities = $this->getRecentActivities();

        return compact(
            'stats',
            'divisiStats',
            'prokerStats',
            'monthlyData',
            'recentActivities'
        );
    }

    private function getStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_news' => News::count(),
            'total_prokers' => Proker::count(),
            'total_notulensi' => Notulensi::count(),
            'active_prokers' => Proker::where('status', Proker::STATUS_ONGOING)->count(),
        ];
    }

    private function getDivisiStats(): array
    {
        $divisi = [
            'redaksi' => 'Divisi Redaksi',
            'litbang' => 'Divisi Litbang',
            'humas' => 'Divisi Humas',
            'media_kreatif' => 'Divisi Media Kreatif',
            'pengurus' => 'Pengurus',
        ];

        $stats = [];

        foreach ($divisi as $key => $nama) {
            $users = User::where(function ($query) use ($key) {
                switch ($key) {
                    case 'redaksi':
                        $query->whereIn('role', [User::ROLE_KOORDINATOR_REDAKSI, User::ROLE_ANGGOTA_REDAKSI]);
                        break;
                    case 'litbang':
                        $query->whereIn('role', [User::ROLE_KOORDINATOR_LITBANG, User::ROLE_ANGGOTA_LITBANG]);
                        break;
                    case 'humas':
                        $query->whereIn('role', [User::ROLE_KOORDINATOR_HUMAS, User::ROLE_ANGGOTA_HUMAS]);
                        break;
                    case 'media_kreatif':
                        $query->whereIn('role', [User::ROLE_KOORDINATOR_MEDIA_KREATIF, User::ROLE_ANGGOTA_MEDIA_KREATIF]);
                        break;
                    case 'pengurus':
                        $query->whereIn('role', [User::ROLE_KOORDINATOR_JURNALISTIK, User::ROLE_SEKRETARIS, User::ROLE_BENDAHARA]);
                        break;
                }
            })->pluck('id');

            $stats[$key] = [
                'nama' => $nama,
                'total' => $users->count(),
                'news' => News::whereIn('user_id', $users)->count(),
                'prokers' => Proker::whereIn('created_by', $users)->count(),
            ];
        }

        return $stats;
    }

    private function getProkerStats(): array
    {
        return [
            'planning' => Proker::where('status', Proker::STATUS_PLANNING)->count(),
            'ongoing' => Proker::where('status', Proker::STATUS_ONGOING)->count(),
            'completed' => Proker::where('status', Proker::STATUS_COMPLETED)->count(),
            'cancelled' => Proker::where('status', Proker::STATUS_CANCELLED)->count(),
        ];
    }

    private function getMonthlyData(): array
    {
        $months = [];
        $news = [];
        

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $news[] = News::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            
        }

        return [
            'labels' => $months,
            'news' => $news,
            
        ];
    }

    private function getRecentActivities(): array
    {
        return [
            'news' => News::latest()->limit(5)->get(),
            'prokers' => Proker::latest()->limit(5)->get(),
            'notulensi' => Notulensi::latest()->limit(5)->get(),
        ];
    }
}

