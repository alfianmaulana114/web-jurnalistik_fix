<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(): View
    {
        $newsCount = News::count();
        $commentCount = Comment::count();
        $userCount = User::count();
        $totalViews = News::sum('views');
        
        // Mengambil data statistik bulanan untuk berita
        $monthlyNews = News::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Mengambil data statistik bulanan untuk komentar
        $monthlyComments = Comment::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Menyiapkan array data bulanan lengkap
        $months = range(1, 12);
        $newsData = array_map(function($month) use ($monthlyNews) {
            return $monthlyNews[$month] ?? 0;
        }, $months);
        
        $commentData = array_map(function($month) use ($monthlyComments) {
            return $monthlyComments[$month] ?? 0;
        }, $months);
        
        $recentNews = News::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        $recentComments = Comment::with('news')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'newsCount',
            'commentCount',
            'userCount',
            'totalViews',
            'recentNews',
            'recentComments',
            'newsData',
            'commentData'
        ));
    }
}