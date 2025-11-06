<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use App\Models\Proker;
use App\Models\Brief;
use App\Models\Content;
use App\Models\Design;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    private function safeCount($modelClass)
    {
        try {
            return $modelClass::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function safeQuery($callback, $default = null)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $default;
        }
    }

    public function getDashboardData(): array
    {
        $newsCount = News::count();
        $commentCount = Comment::count();
        $userCount = User::count();
        $totalViews = News::sum('views');

        $divisiStats = [
            'redaksi' => [
                'coordinators' => User::where('role', User::ROLE_KOORDINATOR_REDAKSI)->count(),
                'members' => User::where('role', User::ROLE_ANGGOTA_REDAKSI)->count(),
                'content' => $this->safeCount(Content::class),
                'briefs' => 0,
                'designs' => 0,
            ],
            'litbang' => [
                'coordinators' => User::where('role', User::ROLE_KOORDINATOR_LITBANG)->count(),
                'members' => User::where('role', User::ROLE_ANGGOTA_LITBANG)->count(),
                'content' => 0,
                'briefs' => $this->safeCount(Brief::class),
                'designs' => 0,
            ],
            'humas' => [
                'coordinators' => User::where('role', User::ROLE_KOORDINATOR_HUMAS)->count(),
                'members' => User::where('role', User::ROLE_ANGGOTA_HUMAS)->count(),
                'content' => 0,
                'briefs' => 0,
                'designs' => 0,
            ],
            'media_kreatif' => [
                'coordinators' => User::where('role', User::ROLE_KOORDINATOR_MEDIA_KREATIF)->count(),
                'members' => User::where('role', User::ROLE_ANGGOTA_MEDIA_KREATIF)->count(),
                'content' => 0,
                'briefs' => 0,
                'designs' => $this->safeCount(Design::class),
            ],
            'pengurus' => [
                'coordinators' => User::where('role', User::ROLE_SEKRETARIS)->count(),
                'members' => User::where('role', User::ROLE_BENDAHARA)->count(),
                'content' => 0,
                'briefs' => 0,
                'designs' => 0,
            ]
        ];

        $prokerStats = [
            'total' => $this->safeCount(Proker::class),
            'active' => $this->safeQuery(function () { return Proker::active()->count(); }, 0),
            'completed' => $this->safeQuery(function () { return Proker::completed()->count(); }, 0),
        ];

        $monthlyNews = News::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyComments = Comment::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $months = range(1, 12);
        $monthlyLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $newsData = array_map(fn($m) => $monthlyNews[$m] ?? 0, $months);
        $commentData = array_map(fn($m) => $monthlyComments[$m] ?? 0, $months);

        $recentNews = News::with('user')->latest()->take(5)->get();
        $recentComments = Comment::with('news')->latest()->take(5)->get();
        $recentProkers = $this->safeQuery(function () {
            return Proker::with('creator')->latest()->take(5)->get();
        }, collect());
        $urgentBriefs = $this->safeQuery(function () {
            return Brief::urgent()->with('creator')->latest()->take(5)->get();
        }, collect());

        return compact(
            'newsCount','commentCount','userCount','totalViews',
            'divisiStats','prokerStats','recentNews','recentComments','recentProkers','urgentBriefs',
            'monthlyLabels','newsData','commentData'
        ) + [
            'totalNews' => $newsCount,
            'totalComments' => $commentCount,
            'totalUsers' => $userCount,
            'divisionStats' => $divisiStats,
        ];
    }
}


