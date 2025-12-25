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
        // ============================================
        // SEMUA DATA DIAMBIL LANGSUNG DARI DATABASE (DINAMIS)
        // ============================================
        
        // Statistik Keuangan - Semua diambil dari database
        // Pemisahan: kas dan pemasukan terpisah
        $totalKasAnggota = $this->getTotalKasAnggota(); // Dari tabel kas_anggota
        $totalPemasukan = $this->getTotalPemasukan(); // Dari tabel pemasukan (TIDAK termasuk kas)
        $totalPengeluaran = $this->getTotalPengeluaran(); // Dari tabel pengeluaran
        // Total saldo = kas + pemasukan - pengeluaran (semua dari database)
        $totalSaldo = ($totalKasAnggota + $totalPemasukan) - $totalPengeluaran;

        // Pemasukan & Pengeluaran Bulan Ini - Semua dari database berdasarkan tanggal
        $pemasukanBulanIni = $this->getPemasukanBulanIni(); // Dari tabel pemasukan
        $kasAnggotaBulanIni = $this->getKasAnggotaBulanIni(); // Dari tabel kas_anggota
        $pengeluaranBulanIni = $this->getPengeluaranBulanIni(); // Dari tabel pengeluaran

        // Total Anggota - Dari tabel users
        $totalAnggota = User::count();

        // Statistik Kas Anggota
        $kasStats = $this->getKasStats();

        // Data Chart
        $chartData = $this->getChartData();

        // Ringkasan Per Divisi
        $ringkasanDivisi = $this->getRingkasanPerDivisi();

        // Anggota Belum Bayar (hanya yang belum bayar bulan ini dan sebelumnya, mulai 2025)
        $anggotaBelumBayar = $this->getAnggotaBelumBayar();

        // Transaksi Terbaru (merge pemasukan, pengeluaran, dan kas)
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
            'anggotaBelumBayar',
            'recentTransactions'
        );
    }

    private function getTotalPemasukan(): float
    {
        // Pemasukan TIDAK termasuk kas anggota (kas dihitung terpisah)
        // Menggunakan scope verified() untuk memastikan hanya yang sudah diverifikasi
        return (float) Pemasukan::verified()->sum('jumlah');
    }

    private function getTotalKasAnggota(): float
    {
        // Total kas anggota yang sudah lunas (dinamis dari database)
        return (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->sum('jumlah_terbayar');
    }

    private function getTotalPengeluaran(): float
    {
        return (float) Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])->sum('jumlah');
    }

    private function getPemasukanBulanIni(): float
    {
        // Pemasukan bulan ini TIDAK termasuk kas (kas dihitung terpisah)
        // Menggunakan tanggal_pemasukan untuk filter bulan ini
        return (float) Pemasukan::verified()
            ->whereYear('tanggal_pemasukan', now()->year)
            ->whereMonth('tanggal_pemasukan', now()->month)
            ->sum('jumlah');
    }

    private function getKasAnggotaBulanIni(): float
    {
        // Kas anggota bulan ini berdasarkan tanggal_pembayaran
        return (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)
            ->whereNotNull('tanggal_pembayaran')
            ->whereYear('tanggal_pembayaran', now()->year)
            ->whereMonth('tanggal_pembayaran', now()->month)
            ->sum('jumlah_terbayar');
    }

    private function getPengeluaranBulanIni(): float
    {
        return (float) Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
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
        $kas = [];
        $pengeluaran = [];
        $incomeCombined = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            // Pemasukan TIDAK termasuk kas - menggunakan scope verified()
            $pemasukanBulan = (float) Pemasukan::verified()
                ->whereYear('tanggal_pemasukan', $date->year)
                ->whereMonth('tanggal_pemasukan', $date->month)
                ->sum('jumlah');
            
            // Kas terpisah - berdasarkan tanggal_pembayaran
            $kasBulan = (float) KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)
                ->whereNotNull('tanggal_pembayaran')
                ->whereYear('tanggal_pembayaran', $date->year)
                ->whereMonth('tanggal_pembayaran', $date->month)
                ->sum('jumlah_terbayar');
            
            // Pengeluaran - menggunakan scope paid()
            $pengeluaranBulan = (float) Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
                ->whereYear('tanggal_pengeluaran', $date->year)
                ->whereMonth('tanggal_pengeluaran', $date->month)
                ->sum('jumlah');
            
            $pemasukan[] = $pemasukanBulan;
            $kas[] = $kasBulan;
            $pengeluaran[] = $pengeluaranBulan;
            $incomeCombined[] = $pemasukanBulan + $kasBulan;
        }

        return [
            'labels' => $months,
            'pemasukan' => $pemasukan,
            'kas' => $kas,
            'pengeluaran' => $pengeluaran,
            'income_combined' => $incomeCombined,
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
        // Pemasukan terbaru (verified) - menggunakan scope verified()
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

        // Kas anggota terbaru (lunas)
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

        // Merge semua transaksi dan urutkan berdasarkan tanggal
        $allTransactions = $recentPemasukan->concat($recentPengeluaran)->concat($recentKas);
        
        return $allTransactions->sortByDesc(function($item) {
            return $item->display_date ? $item->display_date->timestamp : 0;
        })->take(10)->values();
    }
}

