<?php

namespace App\Services\Bendahara;

use App\Models\KasAnggota;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardData(): array
    {
        // Statistik Keuangan
        $totalPemasukan = $this->getTotalPemasukan();
        $totalKasAnggota = $this->getTotalKasAnggota();
        $totalPengeluaran = $this->getTotalPengeluaran();
        $totalSaldo = ($totalPemasukan + $totalKasAnggota) - $totalPengeluaran;

        // Pemasukan & Pengeluaran Bulan Ini
        $pemasukanBulanIni = $this->getPemasukanBulanIni();
        $kasAnggotaBulanIni = $this->getKasAnggotaBulanIni();
        $pengeluaranBulanIni = $this->getPengeluaranBulanIni();

        // Total Anggota
        $totalAnggota = User::count();

        // Statistik Kas Anggota
        $kasStats = $this->getKasStats();

        // Data Chart
        $chartData = $this->getChartData();

        // Ringkasan Per Divisi
        $ringkasanDivisi = $this->getRingkasanPerDivisi();

        // Transaksi Menunggu (merge pemasukan dan pengeluaran)
        $pendingTransactions = $this->getPendingTransactions();

        // Anggota Belum Bayar
        $anggotaBelumBayar = $this->getAnggotaBelumBayar();

        // Transaksi Terbaru (merge pemasukan dan pengeluaran)
        $recentTransactions = $this->getRecentTransactions();

        return compact(
            'totalPemasukan',
            'totalKasAnggota',
            'totalPengeluaran',
            'totalSaldo',
            'pemasukanBulanIni',
            'kasAnggotaBulanIni',
            'pengeluaranBulanIni',
            'totalAnggota',
            'kasStats',
            'chartData',
            'ringkasanDivisi',
            'pendingTransactions',
            'anggotaBelumBayar',
            'recentTransactions'
        );
    }

    private function getTotalPemasukan(): float
    {
        return (float) Pemasukan::where('status', Pemasukan::STATUS_VERIFIED)->sum('jumlah');
    }

    private function getTotalKasAnggota(): float
    {
        return (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->sum('jumlah_terbayar');
    }

    private function getTotalPengeluaran(): float
    {
        return (float) Pengeluaran::where('status', Pengeluaran::STATUS_PAID)->sum('jumlah');
    }

    private function getPemasukanBulanIni(): float
    {
        return (float) Pemasukan::where('status', Pemasukan::STATUS_VERIFIED)
            ->whereYear('tanggal_pemasukan', now()->year)
            ->whereMonth('tanggal_pemasukan', now()->month)
            ->sum('jumlah');
    }

    private function getKasAnggotaBulanIni(): float
    {
        return (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->whereMonth('tanggal_pembayaran', now()->month)
            ->sum('jumlah_terbayar');
    }

    private function getPengeluaranBulanIni(): float
    {
        return (float) Pengeluaran::where('status', Pengeluaran::STATUS_PAID)
            ->whereYear('tanggal_pengeluaran', now()->year)
            ->whereMonth('tanggal_pengeluaran', now()->month)
            ->sum('jumlah');
    }

    private function getKasStats(): array
    {
        return [
            'total_anggota' => KasAnggota::count(),
            'lunas' => KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->count(),
            'belum_lunas' => KasAnggota::whereNotIn('status_pembayaran', [KasAnggota::STATUS_LUNAS])->count(),
            'nunggak' => KasAnggota::where('status_pembayaran', KasAnggota::STATUS_TERLAMBAT)->count(),
            'total_terkumpul' => KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->sum('jumlah_terbayar'),
        ];
    }

    private function getChartData(): array
    {
        $months = [];
        $pemasukan = [];
        $pengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $pemasukanBulan = Pemasukan::where('status', Pemasukan::STATUS_VERIFIED)
                ->whereYear('tanggal_pemasukan', $date->year)
                ->whereMonth('tanggal_pemasukan', $date->month)
                ->sum('jumlah');
            
            $pengeluaranBulan = Pengeluaran::where('status', Pengeluaran::STATUS_PAID)
                ->whereYear('tanggal_pengeluaran', $date->year)
                ->whereMonth('tanggal_pengeluaran', $date->month)
                ->sum('jumlah');
            
            $pemasukan[] = (float) $pemasukanBulan;
            $pengeluaran[] = (float) $pengeluaranBulan;
        }

        return [
            'labels' => $months,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
        ];
    }

    private function getRingkasanPerDivisi(): array
    {
        $divisi = [
            'redaksi' => 'Divisi Redaksi',
            'litbang' => 'Divisi Litbang',
            'humas' => 'Divisi Humas',
            'media_kreatif' => 'Divisi Media Kreatif',
            'pengurus' => 'Pengurus',
        ];

        $ringkasan = [];

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

            $totalAnggota = $users->count();
            $kasLunas = KasAnggota::whereIn('user_id', $users)
                                 ->where('status_pembayaran', KasAnggota::STATUS_LUNAS)
                                 ->count();
            
            $kasBelumLunas = KasAnggota::whereIn('user_id', $users)
                                     ->belumLunas()
                                     ->count();

            $ringkasan[$key] = [
                'nama' => $nama,
                'total' => $totalAnggota,
                'lunas' => $kasLunas,
                'belum_lunas' => $kasBelumLunas,
                'persentase_lunas' => $totalAnggota > 0 ? round(($kasLunas / $totalAnggota) * 100, 1) : 0,
            ];
        }

        return $ringkasan;
    }

    private function getPendingTransactions()
    {
        $pendingPemasukan = Pemasukan::where('status', Pemasukan::STATUS_PENDING)
            ->with('creator')
            ->latest()
            ->limit(5)
            ->get();

        $pendingPengeluaran = Pengeluaran::where('status', Pengeluaran::STATUS_PENDING)
            ->with('creator')
            ->latest()
            ->limit(5)
            ->get();

        // Merge collections
        return $pendingPemasukan->concat($pendingPengeluaran)->sortByDesc('created_at');
    }

    private function getAnggotaBelumBayar()
    {
        return KasAnggota::with('user')
            ->whereNotIn('status_pembayaran', [KasAnggota::STATUS_LUNAS])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($kas) {
                $kas->jumlah_belum_bayar = KasAnggota::getStandardAmount() - $kas->jumlah_terbayar;
                $kas->bulan_tahun = ucfirst($kas->periode) . ' ' . $kas->tahun;
                return $kas;
            });
    }

    private function getRecentTransactions()
    {
        $recentPemasukan = Pemasukan::where('status', Pemasukan::STATUS_VERIFIED)
            ->with('creator')
            ->latest()
            ->limit(5)
            ->get();

        $recentPengeluaran = Pengeluaran::where('status', Pengeluaran::STATUS_PAID)
            ->with('creator')
            ->latest()
            ->limit(5)
            ->get();

        // Merge collections
        return $recentPemasukan->concat($recentPengeluaran)->sortByDesc('created_at');
    }
}

