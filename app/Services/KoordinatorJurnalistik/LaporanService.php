<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\KasAnggota;
use App\Models\KasSetting;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\User;

class LaporanService
{
    public function getLaporanKeuanganData(array $filters): array
    {
        $periode = $filters['periode'] ?? 'bulan_ini';
        $bulan = (int) ($filters['bulan'] ?? now()->month);
        $tahun = (int) ($filters['tahun'] ?? now()->year);
        $tanggalMulai = $filters['tanggal_mulai'] ?? null;
        $tanggalSelesai = $filters['tanggal_selesai'] ?? null;

        $pemasukanQuery = Pemasukan::verified();
        $pengeluaranQuery = Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID]);
        $kasQuery = KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)
            ->whereNotNull('tanggal_pembayaran');

        switch ($periode) {
            case 'bulan_ini':
                $pemasukanQuery->whereYear('tanggal_pemasukan', now()->year)->whereMonth('tanggal_pemasukan', now()->month);
                $pengeluaranQuery->whereYear('tanggal_pengeluaran', now()->year)->whereMonth('tanggal_pengeluaran', now()->month);
                $kasQuery->whereYear('tanggal_pembayaran', now()->year)->whereMonth('tanggal_pembayaran', now()->month);
                break;
            case 'bulan_lalu':
                $date = now()->subMonth();
                $pemasukanQuery->whereYear('tanggal_pemasukan', $date->year)->whereMonth('tanggal_pemasukan', $date->month);
                $pengeluaranQuery->whereYear('tanggal_pengeluaran', $date->year)->whereMonth('tanggal_pengeluaran', $date->month);
                $kasQuery->whereYear('tanggal_pembayaran', $date->year)->whereMonth('tanggal_pembayaran', $date->month);
                break;
            case '3_bulan':
                $start = now()->copy()->subMonths(2)->startOfMonth();
                $end = now()->endOfMonth();
                $pemasukanQuery->whereBetween('tanggal_pemasukan', [$start, $end]);
                $pengeluaranQuery->whereBetween('tanggal_pengeluaran', [$start, $end]);
                $kasQuery->whereBetween('tanggal_pembayaran', [$start, $end]);
                break;
            case '6_bulan':
                $start = now()->copy()->subMonths(5)->startOfMonth();
                $end = now()->endOfMonth();
                $pemasukanQuery->whereBetween('tanggal_pemasukan', [$start, $end]);
                $pengeluaranQuery->whereBetween('tanggal_pengeluaran', [$start, $end]);
                $kasQuery->whereBetween('tanggal_pembayaran', [$start, $end]);
                break;
            case 'tahun_ini':
                $pemasukanQuery->whereYear('tanggal_pemasukan', now()->year);
                $pengeluaranQuery->whereYear('tanggal_pengeluaran', now()->year);
                $kasQuery->whereYear('tanggal_pembayaran', now()->year);
                break;
            case 'tahun_lalu':
                $y = now()->subYear()->year;
                $pemasukanQuery->whereYear('tanggal_pemasukan', $y);
                $pengeluaranQuery->whereYear('tanggal_pengeluaran', $y);
                $kasQuery->whereYear('tanggal_pembayaran', $y);
                break;
            case 'custom':
                if ($tanggalMulai && $tanggalSelesai) {
                    $pemasukanQuery->whereBetween('tanggal_pemasukan', [$tanggalMulai, $tanggalSelesai]);
                    $pengeluaranQuery->whereBetween('tanggal_pengeluaran', [$tanggalMulai, $tanggalSelesai]);
                    $kasQuery->whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalSelesai]);
                } else {
                    $pemasukanQuery->whereYear('tanggal_pemasukan', now()->year)->whereMonth('tanggal_pemasukan', now()->month);
                    $pengeluaranQuery->whereYear('tanggal_pengeluaran', now()->year)->whereMonth('tanggal_pengeluaran', now()->month);
                    $kasQuery->whereYear('tanggal_pembayaran', now()->year)->whereMonth('tanggal_pembayaran', now()->month);
                }
                break;
            default:
                $pemasukanQuery->whereYear('tanggal_pemasukan', now()->year)->whereMonth('tanggal_pemasukan', now()->month);
                $pengeluaranQuery->whereYear('tanggal_pengeluaran', now()->year)->whereMonth('tanggal_pengeluaran', now()->month);
                $kasQuery->whereYear('tanggal_pembayaran', now()->year)->whereMonth('tanggal_pembayaran', now()->month);
                break;
        }

        $pemasukan = $pemasukanQuery->get();
        $pengeluaran = $pengeluaranQuery->get();
        $kas = $kasQuery->get();

        $totalPemasukan = (float) $pemasukan->sum('jumlah');
        $totalPengeluaran = (float) $pengeluaran->sum('jumlah');
        $totalKasAnggota = (float) $kas->sum('jumlah_terbayar');

        $totalSaldo = ($totalKasAnggota + $totalPemasukan) - $totalPengeluaran;

        $pemasukanPerKategori = $pemasukan->groupBy('kategori')->map->sum('jumlah');
        $pengeluaranPerKategori = $pengeluaran->groupBy('kategori')->map->sum('jumlah');

        $jumlahKasPerAnggota = KasSetting::getJumlahKasAnggota();
        $totalAnggota = User::where('role', '!=', 'admin')->count();
        $totalKasSeharusnya = $jumlahKasPerAnggota * $totalAnggota;

        return [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKasAnggota' => $totalKasAnggota,
            'totalSaldo' => $totalSaldo,
            'pemasukanPerKategori' => $pemasukanPerKategori,
            'pengeluaranPerKategori' => $pengeluaranPerKategori,
            'jumlahKasPerAnggota' => $jumlahKasPerAnggota,
            'totalAnggota' => $totalAnggota,
            'totalKasSeharusnya' => $totalKasSeharusnya,
            'filters' => [
                'periode' => $periode,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
            ],
        ];
    }
}
