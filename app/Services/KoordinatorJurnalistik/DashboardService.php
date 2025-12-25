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
        $newsCount = News::count();
        $userCount = User::count();
        $totalViews = News::sum('views');
        $totalBriefs = $this->safeCount(Brief::class);
        $totalContents = $this->safeCount(Content::class);
        $totalDesigns = $this->safeCount(Design::class);
        $totalFunfacts = $this->safeCount(Funfact::class);

        $totalKasAnggota = (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->sum('jumlah_terbayar');
        $totalPemasukan = (float) Pemasukan::verified()->sum('jumlah');
        $totalPengeluaran = (float) Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])->sum('jumlah');
        $totalSaldo = ($totalKasAnggota + $totalPemasukan) - $totalPengeluaran;

        $pemasukanBulanIni = (float) Pemasukan::verified()
            ->whereYear('tanggal_pemasukan', now()->year)
            ->whereMonth('tanggal_pemasukan', now()->month)
            ->sum('jumlah');

        $kasAnggotaBulanIni = (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)
            ->whereNotNull('tanggal_pembayaran')
            ->whereYear('tanggal_pembayaran', now()->year)
            ->whereMonth('tanggal_pembayaran', now()->month)
            ->sum('jumlah_terbayar');

        $pengeluaranBulanIni = (float) Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
            ->whereYear('tanggal_pengeluaran', now()->year)
            ->whereMonth('tanggal_pengeluaran', now()->month)
            ->sum('jumlah');

        $totalAnggota = User::count();

        $monthlyNews = News::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyPemasukan = Pemasukan::verified()
            ->select(DB::raw('MONTH(tanggal_pemasukan) as month'), DB::raw('SUM(jumlah) as total'))
            ->whereYear('tanggal_pemasukan', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyPengeluaran = Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
            ->select(DB::raw('MONTH(tanggal_pengeluaran) as month'), DB::raw('SUM(jumlah) as total'))
            ->whereYear('tanggal_pengeluaran', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Chart data (last 6 months) mirroring Bendahara dashboard: Income (Kas + Pemasukan) vs Pengeluaran
        $chartLabels = [];
        $incomeCombined = [];
        $pengeluaranSeries = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartLabels[] = $date->format('M Y');

            $pemasukanBulan = (float) Pemasukan::verified()
                ->whereYear('tanggal_pemasukan', $date->year)
                ->whereMonth('tanggal_pemasukan', $date->month)
                ->sum('jumlah');

            $kasBulan = (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)
                ->whereNotNull('tanggal_pembayaran')
                ->whereYear('tanggal_pembayaran', $date->year)
                ->whereMonth('tanggal_pembayaran', $date->month)
                ->sum('jumlah_terbayar');

            $pengeluaranBulan = (float) Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
                ->whereYear('tanggal_pengeluaran', $date->year)
                ->whereMonth('tanggal_pengeluaran', $date->month)
                ->sum('jumlah');

            $incomeCombined[] = $pemasukanBulan + $kasBulan;
            $pengeluaranSeries[] = $pengeluaranBulan;
        }
        $chartData = [
            'labels' => $chartLabels,
            'income_combined' => $incomeCombined,
            'pengeluaran' => $pengeluaranSeries,
        ];

        $months = range(1, 12);
        $monthlyLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $newsData = array_map(fn($m) => $monthlyNews[$m] ?? 0, $months);
        $pemasukanData = array_map(fn($m) => $monthlyPemasukan[$m] ?? 0, $months);
        $pengeluaranData = array_map(fn($m) => $monthlyPengeluaran[$m] ?? 0, $months);

        $recentNews = News::with('user')->latest()->take(5)->get();
        $recentProkers = $this->safeQuery(function () { return Proker::with('creator')->latest()->take(5)->get(); }, collect());
        $urgentBriefs = $this->safeQuery(function () { return Brief::urgent()->with('creator')->latest()->take(5)->get(); }, collect());

        $prokerStats = [
            'total' => $this->safeCount(Proker::class),
            'active' => $this->safeQuery(function () { return Proker::active()->count(); }, 0),
            'completed' => $this->safeQuery(function () { return Proker::completed()->count(); }, 0),
        ];

        $unpaidKasMembers = $this->safeQuery(function () {
            return KasAnggota::whereIn('status_pembayaran', [
                    KasAnggota::STATUS_BELUM_BAYAR,
                    KasAnggota::STATUS_SEBAGIAN,
                    KasAnggota::STATUS_TERLAMBAT,
                ])
                ->with('user')
                ->orderBy('tahun', 'desc')
                ->orderBy('periode', 'desc')
                ->get();
        }, collect());

        $content_total = $this->safeCount(Content::class);
        $kas_total_records = $this->safeQuery(function () { return KasAnggota::count(); }, 0);
        $kas_belum_lunas = $this->safeQuery(function () { return KasAnggota::where('status_pembayaran', KasAnggota::STATUS_BELUM_BAYAR)->count(); }, 0);
        $kas_lunas = $this->safeQuery(function () { return KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->count(); }, 0);
        $kas_total_terkumpul = $this->safeQuery(function () { return (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->sum('jumlah_terbayar'); }, 0);

        $anggotaBelumBayar = $this->getAnggotaBelumBayar();
        $recentTransactions = $this->getRecentTransactions();

        return compact(
            'newsCount',
            'userCount',
            'totalViews',
            'totalBriefs',
            'totalContents',
            'totalDesigns',
            'totalFunfacts',
            'totalKasAnggota',
            'totalPemasukan',
            'totalPengeluaran',
            'totalSaldo',
            'pemasukanBulanIni',
            'kasAnggotaBulanIni',
            'pengeluaranBulanIni',
            'totalAnggota',
            'monthlyLabels',
            'newsData',
            'pemasukanData',
            'pengeluaranData',
            'recentNews',
            'recentProkers',
            'urgentBriefs',
            'prokerStats',
            'unpaidKasMembers',
            'anggotaBelumBayar',
            'recentTransactions',
            'chartData'
        ) + [
            'totalNews' => $newsCount,
            'totalUsers' => $userCount,
            'content_total' => $content_total,
            'kas_total_records' => $kas_total_records,
            'kas_belum_lunas' => $kas_belum_lunas,
            'kas_lunas' => $kas_lunas,
            'kas_total_terkumpul' => $kas_total_terkumpul,
        ];
    }

    private function getAnggotaBelumBayar()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $startYear = 2025;
        $periodeOrder = ['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'];
        $users = User::where('role', '!=', 'admin')->get();
        $records = KasAnggota::whereBetween('tahun', [$startYear, $currentYear])->get()->groupBy(function($r){ return $r->user_id.'-'.$r->tahun; });
        $standardAmount = KasAnggota::getStandardAmount();
        $list = collect();
        foreach ($users as $user) {
            $firstUnpaid = null;
            for ($year = $startYear; $year <= $currentYear; $year++) {
                $months = $periodeOrder;
                if ($year === $currentYear) {
                    $months = array_slice($periodeOrder, 0, $currentMonth);
                }
                $key = $user->id.'-'.$year;
                $byYear = $records->get($key);
                foreach ($months as $m) {
                    $rec = $byYear ? $byYear->firstWhere('periode', $m) : null;
                    $isLunas = $rec && $rec->status_pembayaran === KasAnggota::STATUS_LUNAS;
                    if (!$isLunas) {
                        $firstUnpaid = $rec ?: (object) ['periode' => $m, 'tahun' => $year, 'jumlah_terbayar' => 0];
                        break 2;
                    }
                }
            }
            if ($firstUnpaid) {
                $item = $firstUnpaid;
                $item->user = $user;
                $item->jumlah_belum_bayar = $standardAmount - (float) ($item->jumlah_terbayar ?? 0);
                $item->bulan_tahun = ucfirst($item->periode) . ' ' . $item->tahun;
                $item->belum_bayar_dari = $item->bulan_tahun;
                $list->push($item);
            }
        }
        return $list->sortBy(function($kas) use ($periodeOrder) {
            $i = array_search($kas->periode, $periodeOrder);
            $monthIndex = $i !== false ? $i : 0;
            return ($kas->tahun * 12) + $monthIndex;
        })->take(5)->values();
    }

    private function getRecentTransactions()
    {
        $recentPemasukan = Pemasukan::verified()
            ->with('creator')
            ->latest('tanggal_pemasukan')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->type = 'pemasukan';
                $item->display_date = $item->tanggal_pemasukan ?? $item->created_at;
                return $item;
            });

        $recentPengeluaran = Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
            ->with('creator')
            ->latest('tanggal_pengeluaran')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->type = 'pengeluaran';
                $item->display_date = $item->tanggal_pengeluaran ?? $item->created_at;
                return $item;
            });

        $recentKas = KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)
            ->with('user', 'creator')
            ->whereNotNull('tanggal_pembayaran')
            ->latest('tanggal_pembayaran')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->type = 'kas';
                $item->deskripsi = 'Kas Anggota - ' . $item->user->name;
                $item->jumlah = $item->jumlah_terbayar;
                $item->kategori = 'Kas Anggota';
                $item->status = 'lunas';
                $item->display_date = $item->tanggal_pembayaran ?? $item->created_at;
                return $item;
            });

        $allTransactions = $recentPemasukan->concat($recentPengeluaran)->concat($recentKas);
        
        return $allTransactions->sortByDesc(function($item) {
            return $item->display_date ? $item->display_date->timestamp : 0;
        })->take(10)->values();
    }
}


