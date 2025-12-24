<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\News;
use App\Models\User;
use App\Models\Proker;
use App\Models\Brief;
use App\Models\Content;
use App\Models\Design;
use App\Models\KasAnggota;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Funfact;
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
        // General Stats
        $newsCount = News::count();
        $userCount = User::count();
        $totalViews = News::sum('views');
        $totalBriefs = $this->safeCount(Brief::class);
        $totalContents = $this->safeCount(Content::class);
        $totalDesigns = $this->safeCount(Design::class);
        $totalFunfacts = $this->safeCount(Funfact::class);

        // Financial Stats
        $totalPemasukan = $this->safeQuery(function () {
            return Pemasukan::verified()->sum('jumlah');
        }, 0);
        
        $totalPengeluaran = $this->safeQuery(function () {
            return Pengeluaran::paid()->sum('jumlah');
        }, 0);
        
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        $pendingPemasukan = $this->safeQuery(function () {
            return Pemasukan::pending()->sum('jumlah');
        }, 0);
        
        $pendingPengeluaran = $this->safeQuery(function () {
            return Pengeluaran::pending()->sum('jumlah');
        }, 0);

        // Kas Stats
        $kasStats = [
            'belum_bayar' => $this->safeQuery(function () {
                return KasAnggota::where('status_pembayaran', KasAnggota::STATUS_BELUM_BAYAR)->count();
            }, 0),
            'sebagian' => $this->safeQuery(function () {
                return KasAnggota::where('status_pembayaran', KasAnggota::STATUS_SEBAGIAN)->count();
            }, 0),
            'lunas' => $this->safeQuery(function () {
                return KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->count();
            }, 0),
            'terlambat' => $this->safeQuery(function () {
                return KasAnggota::where('status_pembayaran', KasAnggota::STATUS_TERLAMBAT)->count();
            }, 0),
        ];

        // Kas Details for unpaid members
        $unpaidKasMembers = $this->safeQuery(function () {
            return KasAnggota::whereIn('status_pembayaran', [
                KasAnggota::STATUS_BELUM_BAYAR,
                KasAnggota::STATUS_SEBAGIAN,
                KasAnggota::STATUS_TERLAMBAT
            ])
            ->with('user')
            ->orderBy('tahun', 'desc')
            ->orderBy('periode', 'desc')
            ->get();
        }, collect());

        // Division Stats
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
                'coordinators' => User::where('role', User::ROLE_SEKRETARIS)->count() + User::where('role', User::ROLE_KOORDINATOR_JURNALISTIK)->count(),
                'members' => User::where('role', User::ROLE_BENDAHARA)->count(),
                'content' => 0,
                'briefs' => 0,
                'designs' => 0,
            ]
        ];

        // Proker Stats
        $prokerStats = [
            'total' => $this->safeCount(Proker::class),
            'active' => $this->safeQuery(function () { return Proker::active()->count(); }, 0),
            'completed' => $this->safeQuery(function () { return Proker::completed()->count(); }, 0),
        ];

        // Monthly Trends for Charts
        $monthlyNews = News::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Financial Monthly Trends
        $monthlyPemasukan = $this->safeQuery(function () {
            return Pemasukan::verified()
                ->select(DB::raw('MONTH(tanggal_pemasukan) as month'), DB::raw('SUM(jumlah) as total'))
                ->whereYear('tanggal_pemasukan', Carbon::now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();
        }, []);

        $monthlyPengeluaran = $this->safeQuery(function () {
            return Pengeluaran::paid()
                ->select(DB::raw('MONTH(tanggal_pengeluaran) as month'), DB::raw('SUM(jumlah) as total'))
                ->whereYear('tanggal_pengeluaran', Carbon::now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();
        }, []);

        $months = range(1, 12);
        $monthlyLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $newsData = array_map(fn($m) => $monthlyNews[$m] ?? 0, $months);
        $pemasukanData = array_map(fn($m) => $monthlyPemasukan[$m] ?? 0, $months);
        $pengeluaranData = array_map(fn($m) => $monthlyPengeluaran[$m] ?? 0, $months);

        // Recent Activities
        $recentNews = News::with('user')->latest()->take(5)->get();
        $recentProkers = $this->safeQuery(function () {
            return Proker::with('creator')->latest()->take(5)->get();
        }, collect());
        $urgentBriefs = $this->safeQuery(function () {
            return Brief::urgent()->with('creator')->latest()->take(5)->get();
        }, collect());

        return compact(
            'newsCount',
            'userCount',
            'totalViews',
            'totalBriefs',
            'totalContents',
            'totalDesigns',
            'totalFunfacts',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'pendingPemasukan',
            'pendingPengeluaran',
            'kasStats',
            'unpaidKasMembers',
            'divisiStats',
            'prokerStats',
            'recentNews',
            'recentProkers',
            'urgentBriefs',
            'monthlyLabels',
            'newsData',
            'pemasukanData',
            'pengeluaranData'
        ) + [
            'totalNews' => $newsCount,
            'totalUsers' => $userCount,
            'divisionStats' => $divisiStats,
        ];
    }
}


