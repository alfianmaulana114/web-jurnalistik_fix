<?php

namespace App\Services\Sekretaris;

use App\Models\User;
use App\Models\Proker;
use App\Models\News;
use App\Models\Notulensi;
use App\Models\Absen;
use App\Models\KasAnggota;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardData(): array
    {
        $stats = $this->getStats();
        $prokerStats = $this->getProkerStats();
        $recentActivities = $this->getRecentActivities();
        $finance = $this->getFinanceOverview();
        $financeChart = $this->getFinanceChart();
        $topAbsent = $this->getTopAbsent();

        return compact(
            'stats',
            'prokerStats',
            'recentActivities',
            'finance',
            'financeChart',
            'topAbsent'
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

    private function getFinanceOverview(): array
    {
        $totalPemasukan = (float) Pemasukan::verified()
            ->whereYear('tanggal_pemasukan', now()->year)
            ->whereMonth('tanggal_pemasukan', now()->month)
            ->sum('jumlah');

        $totalPengeluaran = (float) Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
            ->whereYear('tanggal_pengeluaran', now()->year)
            ->whereMonth('tanggal_pengeluaran', now()->month)
            ->sum('jumlah');

        $overallKas = (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->sum('jumlah_terbayar');
        $overallPemasukan = (float) Pemasukan::verified()->sum('jumlah');
        $overallPengeluaran = (float) Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])->sum('jumlah');
        $totalSaldo = ($overallKas + $overallPemasukan) - $overallPengeluaran;

        return compact('totalPemasukan', 'totalPengeluaran', 'totalSaldo');
    }

    private function getFinanceChart(): array
    {
        $labels = [];
        $pemasukan = [];
        $kas = [];
        $pengeluaran = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
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

            $pemasukan[] = $pemasukanBulan;
            $kas[] = $kasBulan;
            $pengeluaran[] = $pengeluaranBulan;
        }

        $income_combined = [];
        for ($i = 0; $i < count($labels); $i++) {
            $income_combined[$i] = ($pemasukan[$i] ?? 0) + ($kas[$i] ?? 0);
        }

        return compact('labels', 'pemasukan', 'kas', 'pengeluaran', 'income_combined');
    }

    private function getTopAbsent(): array
    {
        // Ambil 5 anggota dengan jumlah tidak hadir terbanyak
        $top = Absen::selectRaw('user_id, COUNT(*) as total_tidak_hadir')
            ->where('status', Absen::STATUS_TIDAK_HADIR)
            ->whereYear('tanggal', '>=', 2025)
            ->groupBy('user_id')
            ->orderByDesc('total_tidak_hadir')
            ->limit(5)
            ->get();

        // Map ke data dengan nama pengguna
        return $top->map(function ($row) {
            $user = User::find($row->user_id);
            return [
                'user_id' => $row->user_id,
                'name' => $user?->name ?? 'Anggota',
                'nim' => $user?->nim ?? null,
                'divisi' => $user?->getDivision() ?? null,
                'total' => (int) $row->total_tidak_hadir,
            ];
        })->toArray();
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

    

    private function getRecentActivities(): array
    {
        return [
            'news' => News::latest()->limit(5)->get(),
            'prokers' => Proker::latest()->limit(5)->get(),
            'notulensi' => Notulensi::latest()->limit(5)->get(),
        ];
    }
}

