<?php

namespace App\Services\Bendahara;

use App\Models\KasAnggota;
use App\Models\KasSetting;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\User;

class LaporanService
{
    public function getLaporanKeuanganData(int $bulan, int $tahun): array
    {
        $pemasukan = Pemasukan::verified()
            ->whereMonth('tanggal_pemasukan', $bulan)
            ->whereYear('tanggal_pemasukan', $tahun)
            ->get();

        $pengeluaran = Pengeluaran::paid()
            ->whereMonth('tanggal_pengeluaran', $bulan)
            ->whereYear('tanggal_pengeluaran', $tahun)
            ->get();

        $totalPemasukan = $pemasukan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');

        $jumlahKasPerAnggota = KasSetting::getJumlahKasAnggota();
        $totalAnggota = User::where('role', '!=', 'admin')->count();
        $totalKasSeharusnya = $jumlahKasPerAnggota * $totalAnggota;
        $totalKasTerkumpul = KasAnggota::where('status_pembayaran', 'lunas')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->sum('jumlah_terbayar');

        $pemasukanPerKategori = $pemasukan->groupBy('kategori')->map->sum('jumlah');
        $pengeluaranPerKategori = $pengeluaran->groupBy('kategori')->map->sum('jumlah');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'jumlahKasPerAnggota',
            'totalAnggota',
            'totalKasSeharusnya',
            'totalKasTerkumpul',
            'pemasukanPerKategori',
            'pengeluaranPerKategori'
        );
    }
}


