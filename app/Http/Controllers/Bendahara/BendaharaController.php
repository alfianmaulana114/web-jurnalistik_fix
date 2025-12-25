<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\KasAnggota;
use App\Models\KasSetting;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BendaharaController extends Controller
{
    // Middleware sudah di-handle di route level dengan 'role:bendahara'

    /**
     * Menampilkan dashboard bendahara dengan statistik keuangan.
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\Bendahara\DashboardService::class)->getDashboardData();
        return view('bendahara.dashboard', $data);
    }
    
    /**
     * Menampilkan daftar kas anggota
     */
    public function kasAnggotaIndex(Request $request)
    {
        try {
            $query = KasAnggota::with(['user', 'creator']);

            // Filter berdasarkan periode
            if ($request->filled('periode')) {
                $query->where('periode', $request->periode);
            }

            // Filter berdasarkan tahun
            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $status = $request->status;
                if ($status === 'belum_lunas') {
                    $query->belumLunas();
                } elseif ($status === 'nunggak') {
                    $query->where('status_pembayaran', KasAnggota::STATUS_TERLAMBAT);
                } else {
                    $query->where('status_pembayaran', $status);
                }
            }

            // Search berdasarkan nama anggota
            if ($request->filled('search')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('nim', 'like', '%' . $request->search . '%');
                });
            }

            $kasAnggota = $query->orderBy('created_at', 'desc')->paginate(15);

            // Hitung statistik
            $stats = [
                'lunas' => KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->count(),
                'belum_lunas' => KasAnggota::belumLunas()->count(),
                'nunggak' => KasAnggota::where('status_pembayaran', KasAnggota::STATUS_TERLAMBAT)->count(),
                'total_terkumpul' => KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->sum('jumlah_terbayar')
            ];

            // Data untuk filter
            $periodeOptions = KasAnggota::getAllPeriode();
            $statusOptions = KasAnggota::getAllStatusPembayaran();
            $tahunOptions = KasAnggota::distinct()->pluck('tahun')->sort()->values();

            return view('bendahara.kas-anggota.index', compact(
                'kasAnggota',
                'periodeOptions',
                'statusOptions',
                'tahunOptions',
                'stats'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat data kas anggota: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk membuat kas anggota baru
     */
    public function kasAnggotaCreate()
    {
        try {
            $users = User::whereNotIn('role', [User::ROLE_KOORDINATOR_JURNALISTIK])
                        ->orderBy('name')
                        ->get();
            
            $periodeOptions = KasAnggota::getAllPeriode();

            return view('bendahara.kas-anggota.create', compact('users', 'periodeOptions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan kas anggota baru
     */
    public function kasAnggotaStore(Request $request)
    {
        try {
            return app(\App\Services\Bendahara\KasAnggotaService::class)->store($request);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Menampilkan detail kas anggota
     */
    public function kasAnggotaShow(KasAnggota $kasAnggota)
    {
        try {
            $kasAnggota->load(['user', 'creator', 'updater', 'pemasukan.creator']);
            
            return view('bendahara.kas-anggota.show', compact('kasAnggota'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat detail: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit kas anggota
     */
    public function kasAnggotaEdit(KasAnggota $kasAnggota)
    {
        try {
            $users = User::whereNotIn('role', [User::ROLE_KOORDINATOR_JURNALISTIK])
                        ->orderBy('name')
                        ->get();
            
            $periodeOptions = KasAnggota::getAllPeriode();
            $tahunOptions = KasAnggota::distinct()->pluck('tahun')->sort()->values();

            return view('bendahara.kas-anggota.edit', compact('kasAnggota', 'users', 'periodeOptions', 'tahunOptions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form edit: ' . $e->getMessage());
        }
    }

    /**
     * Update kas anggota
     */
    public function kasAnggotaUpdate(Request $request, KasAnggota $kasAnggota)
    {
        try {
            return app(\App\Services\Bendahara\KasAnggotaService::class)->update($request, $kasAnggota);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Hapus kas anggota
     */
    public function kasAnggotaDestroy(KasAnggota $kasAnggota)
    {
        try {
            return app(\App\Services\Bendahara\KasAnggotaService::class)->destroy($kasAnggota);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan riwayat kas anggota
     */
    public function kasAnggotaRiwayat(Request $request)
    {
        try {
            $data = app(\App\Services\Bendahara\KasAnggotaService::class)->getRiwayat($request->all());
            return view('bendahara.kas-anggota.riwayat', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat riwayat kas: ' . $e->getMessage());
        }
    }

    /**
     * Mendapatkan data chart untuk dashboard
     */
    private function getChartData(): array
    {
        $months = [];
        $pemasukan = [];
        $pengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $pemasukanBulan = Pemasukan::verified()
                ->whereYear('tanggal_pemasukan', $date->year)
                ->whereMonth('tanggal_pemasukan', $date->month)
                ->sum('jumlah');
            
            $pengeluaranBulan = Pengeluaran::paid()
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

    /**
     * Mendapatkan ringkasan keuangan per divisi
     */
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

    // ==================== PEMASUKAN METHODS ====================

    /**
     * Menampilkan halaman manajemen pemasukan dengan filter dan statistik.
     */
    public function pemasukanIndex(Request $request)
    {
        try {
            $data = app(\App\Services\Bendahara\PemasukanService::class)->getIndexData($request->all());
            return view('bendahara.pemasukan.index', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data pemasukan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk menambah pemasukan baru.
     */
    public function pemasukanCreate()
    {
        try {
            $kasAnggotaBelumLunas = KasAnggota::with('user')->belumLunas()->get();
            $kategoriOptions = Pemasukan::getAllKategori();
            $metodePembayaranOptions = Pemasukan::getAllMetodePembayaran();

            return view('bendahara.pemasukan.create', compact(
                'kasAnggotaBelumLunas',
                'kategoriOptions',
                'metodePembayaranOptions'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan pemasukan baru
     */
    public function pemasukanStore(Request $request)
    {
        try {
            return app(\App\Services\Bendahara\PemasukanService::class)->store($request);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Menampilkan detail pemasukan
     */
    public function pemasukanShow(Pemasukan $pemasukan)
    {
        try {
            $pemasukan->load(['kasAnggota.user', 'creator', 'verifier']);
            
            return view('bendahara.pemasukan.show', compact('pemasukan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat detail: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit pemasukan
     */
    public function pemasukanEdit(Pemasukan $pemasukan)
    {
        try {
            $kasAnggota = KasAnggota::with('user')->get();
            $kategoriOptions = Pemasukan::getAllKategori();
            $metodePembayaranOptions = Pemasukan::getAllMetodePembayaran();

            return view('bendahara.pemasukan.edit', compact(
                'pemasukan',
                'kasAnggota',
                'kategoriOptions',
                'metodePembayaranOptions'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form edit: ' . $e->getMessage());
        }
    }

    /**
     * Update pemasukan
     */
    public function pemasukanUpdate(Request $request, Pemasukan $pemasukan)
    {
        try {
            return app(\App\Services\Bendahara\PemasukanService::class)->update($request, $pemasukan);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Hapus pemasukan
     */
    public function pemasukanDestroy(Pemasukan $pemasukan)
    {
        try {
            return app(\App\Services\Bendahara\PemasukanService::class)->destroy($pemasukan);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi pemasukan
     */
    public function pemasukanVerify(Pemasukan $pemasukan)
    {
        try {
            return app(\App\Services\Bendahara\PemasukanService::class)->verify($pemasukan);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage());
        }
    }

    // ==================== PENGELUARAN METHODS ====================

    /**
     * Menampilkan daftar pengeluaran
     */
    public function pengeluaranIndex(Request $request)
    {
        try {
            $data = app(\App\Services\Bendahara\PengeluaranService::class)->getIndexData($request->all());
            return view('bendahara.pengeluaran.index', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat data pengeluaran: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk membuat pengeluaran baru
     */
    public function pengeluaranCreate()
    {
        try {
            $kategoriOptions = Pengeluaran::getAllKategori();
            $metodePembayaranOptions = Pengeluaran::getAllMetodePembayaran();

            return view('bendahara.pengeluaran.create', compact(
                'kategoriOptions',
                'metodePembayaranOptions'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan pengeluaran baru
     */
    public function pengeluaranStore(Request $request)
    {
        try {
            return app(\App\Services\Bendahara\PengeluaranService::class)->store($request);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Menampilkan detail pengeluaran
     */
    public function pengeluaranShow(Pengeluaran $pengeluaran)
    {
        try {
            $pengeluaran->load(['creator', 'approver', 'payer']);
            
            return view('bendahara.pengeluaran.show', compact('pengeluaran'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat detail: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit pengeluaran
     */
    public function pengeluaranEdit(Pengeluaran $pengeluaran)
    {
        try {
            $kategoriOptions = Pengeluaran::getAllKategori();
            $metodePembayaranOptions = Pengeluaran::getAllMetodePembayaran();

            return view('bendahara.pengeluaran.edit', compact(
                'pengeluaran',
                'kategoriOptions',
                'metodePembayaranOptions'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form edit: ' . $e->getMessage());
        }
    }

    /**
     * Update pengeluaran
     */
    public function pengeluaranUpdate(Request $request, Pengeluaran $pengeluaran)
    {
        try {
            return app(\App\Services\Bendahara\PengeluaranService::class)->update($request, $pengeluaran);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Hapus pengeluaran
     */
    public function pengeluaranDestroy(Pengeluaran $pengeluaran)
    {
        try {
            return app(\App\Services\Bendahara\PengeluaranService::class)->destroy($pengeluaran);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi pengeluaran (alias untuk approve)
     */
    public function pengeluaranVerify(Pengeluaran $pengeluaran)
    {
        try {
            return app(\App\Services\Bendahara\PengeluaranService::class)->approve($pengeluaran);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Approve pengeluaran
     */
    public function pengeluaranApprove(Pengeluaran $pengeluaran)
    {
        try {
            return app(\App\Services\Bendahara\PengeluaranService::class)->approve($pengeluaran);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyetujui: ' . $e->getMessage());
        }
    }

    /**
     * Mark pengeluaran as paid
     */
    public function pengeluaranPay(Pengeluaran $pengeluaran)
    {
        try {
            return app(\App\Services\Bendahara\PengeluaranService::class)->pay($pengeluaran);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    // ==================== LAPORAN METHODS ====================

    /**
     * Menampilkan halaman laporan keuangan
     */
    public function laporanKeuangan(Request $request)
    {
        try {
            $filters = [
                'periode' => $request->get('periode', 'bulan_ini'),
                'bulan' => (int) $request->get('bulan', now()->month),
                'tahun' => (int) $request->get('tahun', now()->year),
                'tanggal_mulai' => $request->get('tanggal_mulai'),
                'tanggal_selesai' => $request->get('tanggal_selesai'),
            ];
            $data = app(\App\Services\Bendahara\LaporanService::class)->getLaporanKeuanganData($filters);
            return view('bendahara.laporan.index', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan keuangan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan laporan kas anggota dengan filter sederhana
     */
    public function laporanKasAnggota(Request $request)
    {
        try {
            $bulan = (int) $request->get('bulan', now()->month);
            $tahun = (int) $request->get('tahun', now()->year);

            // Map bulan angka ke teks sesuai model KasAnggota::byPeriode
            $bulanMap = [
                1 => 'januari', 2 => 'februari', 3 => 'maret', 4 => 'april', 5 => 'mei', 6 => 'juni',
                7 => 'juli', 8 => 'agustus', 9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'desember'
            ];
            $periode = $bulanMap[$bulan] ?? strtolower(now()->format('F'));

            $query = KasAnggota::with(['user'])
                ->where('tahun', $tahun)
                ->where('periode', $periode)
                ->orderBy('user_id');

            if ($request->filled('status')) {
                $query->where('status_pembayaran', $request->status);
            }

            $kasAnggota = $query->get();

            $jumlahKasPerAnggota = KasSetting::getJumlahKasAnggota();
            $totalSeharusnya = $jumlahKasPerAnggota * $kasAnggota->count();
            $totalTerkumpul = (float) $kasAnggota->sum('jumlah_terbayar');

            return view('bendahara.laporan.kas-anggota', compact(
                'kasAnggota',
                'bulan',
                'tahun',
                'periode',
                'jumlahKasPerAnggota',
                'totalSeharusnya',
                'totalTerkumpul'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan kas anggota: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel laporan: kas-anggota | pemasukan | pengeluaran
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $type = $request->get('type');
        $periode = $request->get('periode');
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        if (!in_array($type, ['kas-anggota', 'pemasukan', 'pengeluaran', 'total-saldo'], true)) {
            abort(400, 'Tipe export tidak valid.');
        }

        // Siapkan dataset sesuai tipe
        if ($type === 'kas-anggota') {
            $bulanMap = [
                1 => 'januari', 2 => 'februari', 3 => 'maret', 4 => 'april', 5 => 'mei', 6 => 'juni',
                7 => 'juli', 8 => 'agustus', 9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'desember'
            ];
            $periode = $bulanMap[$bulan] ?? strtolower(now()->format('F'));
            $standardAmount = \App\Models\KasSetting::getJumlahKasAnggota();
            $rows = KasAnggota::with(['user', 'creator', 'updater'])
                ->where('tahun', $tahun)
                ->where('periode', $periode)
                ->orderBy('user_id')
                ->get()
                ->map(function ($item) use ($standardAmount) {
                    $user = optional($item->user);
                    $divisi = method_exists($user, 'getDivision') ? ucfirst(str_replace('_',' ', $user->getDivision())) : '';
                    $kekurangan = max(0, (float) $standardAmount - (float) $item->jumlah_terbayar);
                    return [
                        'Nama' => $user->name,
                        'NIM' => $user->nim,
                        'Divisi' => $divisi,
                        'Tahun' => (int) $item->tahun,
                        'Periode' => ucfirst($item->periode),
                        'Status Pembayaran' => $item->status_pembayaran,
                        'Jumlah Terbayar' => (float) $item->jumlah_terbayar,
                        'Jumlah Seharusnya' => (float) $standardAmount,
                        'Kekurangan' => (float) $kekurangan,
                        'Tanggal Pembayaran' => optional($item->tanggal_pembayaran)->format('Y-m-d'),
                        'Dibuat Oleh' => optional($item->creator)->name,
                        'Terakhir Diperbarui Oleh' => optional($item->updater)->name,
                        'Keterangan' => $item->keterangan,
                    ];
                })->values();
        } elseif ($type === 'pemasukan') {
            $query = Pemasukan::verified()->with(['creator', 'verifier', 'user']);
            if ($periode === 'custom' && $tanggalMulai && $tanggalSelesai) {
                $query->whereBetween('tanggal_pemasukan', [$tanggalMulai, $tanggalSelesai]);
            } else {
                $query->whereMonth('tanggal_pemasukan', $bulan)->whereYear('tanggal_pemasukan', $tahun);
            }
            $rows = $query->orderBy('tanggal_pemasukan')
                ->get()
                ->map(function ($item) {
                    $user = optional($item->user);
                    $divisi = method_exists($user, 'getDivision') ? ucfirst(str_replace('_',' ', $user->getDivision())) : '';
                    return [
                        'Kode Transaksi' => $item->kode_transaksi,
                        'Tanggal' => optional($item->tanggal_pemasukan)->format('Y-m-d'),
                        'Sumber' => $item->sumber_pemasukan,
                        'Kategori' => $item->getKategoriLabel(),
                        'Jumlah' => (float) $item->jumlah,
                        'Metode' => $item->getMetodePembayaranOptions()[$item->metode_pembayaran] ?? $item->metode_pembayaran,
                        'Nomor Referensi' => $item->nomor_referensi,
                        'Status' => $item->getStatusLabel(),
                        'Dibuat Oleh' => optional($item->creator)->name,
                        'Dibuat Pada' => optional($item->created_at)->format('Y-m-d H:i'),
                        'Diverifikasi Oleh' => optional($item->verifier)->name,
                        'Diverifikasi Pada' => optional($item->verified_at)->format('Y-m-d H:i'),
                        'Pembayar' => $user->name,
                        'NIM' => $user->nim,
                        'Divisi' => $divisi,
                        'Deskripsi' => $item->deskripsi,
                        'Keterangan' => $item->keterangan,
                    ];
                })->values();
        } elseif ($type === 'pengeluaran') {
            $query = Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
                ->with(['creator', 'approver', 'payer']);
            if ($periode === 'custom' && $tanggalMulai && $tanggalSelesai) {
                $query->whereBetween('tanggal_pengeluaran', [$tanggalMulai, $tanggalSelesai]);
            } else {
                $query->whereMonth('tanggal_pengeluaran', $bulan)->whereYear('tanggal_pengeluaran', $tahun);
            }
            $rows = $query->orderBy('tanggal_pengeluaran')
                ->get()
                ->map(function ($item) {
                    return [
                        'Kode Transaksi' => $item->kode_transaksi,
                        'Tanggal' => optional($item->tanggal_pengeluaran)->format('Y-m-d'),
                        'Keperluan' => $item->keperluan,
                        'Kategori' => $item->getKategoriLabel(),
                        'Jumlah' => (float) $item->jumlah,
                        'Metode' => $item->getMetodePembayaranLabel(),
                        'Nomor Referensi' => $item->nomor_referensi,
                        'Penerima' => $item->penerima,
                        'Status' => $item->getStatusLabel(),
                        'Dibuat Oleh' => optional($item->creator)->name,
                        'Disetujui Oleh' => optional($item->approver)->name,
                        'Disetujui Pada' => optional($item->approved_at)->format('Y-m-d H:i'),
                        'Dibayar Oleh' => optional($item->payer)->name,
                        'Dibayar Pada' => optional($item->paid_at)->format('Y-m-d H:i'),
                        'Deskripsi' => $item->deskripsi,
                        'Keterangan' => $item->keterangan,
                    ];
                })->values();
        } else {
            $filters = [
                'periode' => $periode ?? 'bulan_ini',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
            ];
            $data = app(\App\Services\Bendahara\LaporanService::class)->getLaporanKeuanganData($filters);
            $rows = collect();
            $rows->push(['Komponen' => 'Total Kas', 'Nilai' => (float) $data['totalKasAnggota']]);
            $rows->push(['Komponen' => 'Total Pemasukan', 'Nilai' => (float) $data['totalPemasukan']]);
            $rows->push(['Komponen' => 'Total Pengeluaran', 'Nilai' => (float) $data['totalPengeluaran']]);
            $rows->push(['Komponen' => 'Total Saldo', 'Nilai' => (float) $data['totalSaldo']]);
        }

        $usePhpSpreadsheet = class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class);
        $zipAvailable = extension_loaded('zip');

        $filenameBase = 'laporan-' . $type . '-' . $tahun . '-' . str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);

        if ($usePhpSpreadsheet) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $makeSheet = function($spreadsheet, string $title, \Illuminate\Support\Collection $rows) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle($title);
                $headers = array_keys($rows->first() ?? []);
                $titleText = 'Laporan ' . $title;
                $lastCol = $headers ? \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers)) : 'A';
                $sheet->setCellValue('A1', $titleText);
                $sheet->mergeCells('A1:' . $lastCol . '1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                foreach ($headers as $colIndex => $header) {
                    $sheet->setCellValueByColumnAndRow($colIndex + 1, 2, $header);
                }

                $rowIndex = 3;
                foreach ($rows as $row) {
                    $colIndex = 1;
                    foreach ($headers as $header) {
                        $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $row[$header] ?? '');
                        $colIndex++;
                    }
                    $rowIndex++;
                }

                $headerRange = 'A2:' . $lastCol . '2';
                $dataRange = 'A2:' . $lastCol . ($rowIndex - 1);
                $sheet->getStyle($headerRange)->getFont()->setBold(true);
                $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
                $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->freezePane('A3');
                $sheet->setAutoFilter($headerRange);

                for ($i = 1; $i <= count($headers); $i++) {
                    $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
                }

                foreach ($headers as $idx => $header) {
                    $needle = ['Jumlah', 'Nilai', 'Total', 'Kekurangan'];
                    foreach ($needle as $n) {
                        if (stripos($header, $n) !== false) {
                            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx + 1);
                            $sheet->getStyle($colLetter . '3:' . $colLetter . ($rowIndex - 1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            break;
                        }
                    }
                }

                if ($rows->count() > 0) {
                    $sumColIndex = null;
                    foreach ($headers as $idx => $h) {
                        if (stripos($h, 'Jumlah') !== false || stripos($h, 'Nilai') !== false) {
                            $sumColIndex = $idx + 1;
                            break;
                        }
                    }
                    if ($sumColIndex) {
                        $sumColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($sumColIndex);
                        $sheet->setCellValue('A' . $rowIndex, 'Total');
                        $sheet->setCellValue($sumColLetter . $rowIndex, '=SUM(' . $sumColLetter . '3:' . $sumColLetter . ($rowIndex - 1) . ')');
                        $sheet->getStyle('A' . $rowIndex . ':' . $lastCol . $rowIndex)->getFont()->setBold(true);
                    }
                }

                return $sheet;
            };

            if ($type === 'total-saldo') {
                $summaryRows = $rows; // from computed earlier

                $spreadsheet->setActiveSheetIndex(0);
                $makeSheet($spreadsheet, 'Total Saldo', $summaryRows);

                $queryPemasukan = Pemasukan::verified()->with(['creator', 'verifier', 'user']);
                if ($periode === 'custom' && $tanggalMulai && $tanggalSelesai) {
                    $queryPemasukan->whereBetween('tanggal_pemasukan', [$tanggalMulai, $tanggalSelesai]);
                } else {
                    $queryPemasukan->whereMonth('tanggal_pemasukan', $bulan)->whereYear('tanggal_pemasukan', $tahun);
                }
                $pemasukanRows = $queryPemasukan->orderBy('tanggal_pemasukan')
                    ->get()
                    ->map(function ($item) {
                        $user = optional($item->user);
                        $divisi = method_exists($user, 'getDivision') ? ucfirst(str_replace('_',' ', $user->getDivision())) : '';
                        return [
                            'Kode Transaksi' => $item->kode_transaksi,
                            'Tanggal' => optional($item->tanggal_pemasukan)->format('Y-m-d'),
                            'Sumber' => $item->sumber_pemasukan,
                            'Kategori' => $item->getKategoriLabel(),
                            'Jumlah' => (float) $item->jumlah,
                            'Metode' => $item->getMetodePembayaranOptions()[$item->metode_pembayaran] ?? $item->metode_pembayaran,
                            'Nomor Referensi' => $item->nomor_referensi,
                            'Status' => $item->getStatusLabel(),
                            'Dibuat Oleh' => optional($item->creator)->name,
                            'Dibuat Pada' => optional($item->created_at)->format('Y-m-d H:i'),
                            'Diverifikasi Oleh' => optional($item->verifier)->name,
                            'Diverifikasi Pada' => optional($item->verified_at)->format('Y-m-d H:i'),
                            'Pembayar' => $user->name,
                            'NIM' => $user->nim,
                            'Divisi' => $divisi,
                            'Deskripsi' => $item->deskripsi,
                            'Keterangan' => $item->keterangan,
                        ];
                    })->values();

                $spreadsheet->createSheet();
                $spreadsheet->setActiveSheetIndex(1);
                $makeSheet($spreadsheet, 'Pemasukan', $pemasukanRows);

                $queryPengeluaran = Pengeluaran::whereIn('status', [Pengeluaran::STATUS_APPROVED, Pengeluaran::STATUS_PAID])
                    ->with(['creator', 'approver', 'payer']);
                if ($periode === 'custom' && $tanggalMulai && $tanggalSelesai) {
                    $queryPengeluaran->whereBetween('tanggal_pengeluaran', [$tanggalMulai, $tanggalSelesai]);
                } else {
                    $queryPengeluaran->whereMonth('tanggal_pengeluaran', $bulan)->whereYear('tanggal_pengeluaran', $tahun);
                }
                $pengeluaranRows = $queryPengeluaran->orderBy('tanggal_pengeluaran')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'Kode Transaksi' => $item->kode_transaksi,
                            'Tanggal' => optional($item->tanggal_pengeluaran)->format('Y-m-d'),
                            'Keperluan' => $item->keperluan,
                            'Kategori' => $item->getKategoriLabel(),
                            'Jumlah' => (float) $item->jumlah,
                            'Metode' => $item->getMetodePembayaranLabel(),
                            'Nomor Referensi' => $item->nomor_referensi,
                            'Penerima' => $item->penerima,
                            'Status' => $item->getStatusLabel(),
                            'Dibuat Oleh' => optional($item->creator)->name,
                            'Disetujui Oleh' => optional($item->approver)->name,
                            'Disetujui Pada' => optional($item->approved_at)->format('Y-m-d H:i'),
                            'Dibayar Oleh' => optional($item->payer)->name,
                            'Dibayar Pada' => optional($item->paid_at)->format('Y-m-d H:i'),
                            'Deskripsi' => $item->deskripsi,
                            'Keterangan' => $item->keterangan,
                        ];
                    })->values();

                $spreadsheet->createSheet();
                $spreadsheet->setActiveSheetIndex(2);
                $makeSheet($spreadsheet, 'Pengeluaran', $pengeluaranRows);

                $bulanMap = [
                    1 => 'januari', 2 => 'februari', 3 => 'maret', 4 => 'april', 5 => 'mei', 6 => 'juni',
                    7 => 'juli', 8 => 'agustus', 9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'desember'
                ];
                $periodeKas = $bulanMap[$bulan] ?? strtolower(now()->format('F'));
                $standardAmount = \App\Models\KasSetting::getJumlahKasAnggota();
                $kasRows = KasAnggota::with(['user', 'creator', 'updater'])
                    ->where('tahun', $tahun)
                    ->where('periode', $periodeKas)
                    ->orderBy('user_id')
                    ->get()
                    ->map(function ($item) use ($standardAmount) {
                        $user = optional($item->user);
                        $divisi = method_exists($user, 'getDivision') ? ucfirst(str_replace('_',' ', $user->getDivision())) : '';
                        $kekurangan = max(0, (float) $standardAmount - (float) $item->jumlah_terbayar);
                        return [
                            'Nama' => $user->name,
                            'NIM' => $user->nim,
                            'Divisi' => $divisi,
                            'Tahun' => (int) $item->tahun,
                            'Periode' => ucfirst($item->periode),
                            'Status Pembayaran' => $item->status_pembayaran,
                            'Jumlah Terbayar' => (float) $item->jumlah_terbayar,
                            'Jumlah Seharusnya' => (float) $standardAmount,
                            'Kekurangan' => (float) $kekurangan,
                            'Tanggal Pembayaran' => optional($item->tanggal_pembayaran)->format('Y-m-d'),
                            'Dibuat Oleh' => optional($item->creator)->name,
                            'Terakhir Diperbarui Oleh' => optional($item->updater)->name,
                            'Keterangan' => $item->keterangan,
                        ];
                    })->values();

                $spreadsheet->createSheet();
                $spreadsheet->setActiveSheetIndex(3);
                $makeSheet($spreadsheet, 'Kas Anggota', $kasRows);

                $filename = $filenameBase . ($zipAvailable ? '.xlsx' : '.xls');
                $writer = $zipAvailable
                    ? new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet)
                    : new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                $contentType = $zipAvailable ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'application/vnd.ms-excel';
                $spreadsheet->setActiveSheetIndex(0);

                return new StreamedResponse(function () use ($writer) {
                    $writer->save('php://output');
                }, 200, [
                    'Content-Type' => $contentType,
                    'Content-Disposition' => 'attachment;filename="' . $filename . '"',
                    'Cache-Control' => 'max-age=0',
                ]);
            }

            $sheet = $spreadsheet->getActiveSheet();
            $headers = array_keys($rows->first() ?? []);
            $title = 'Laporan ' . str_replace('-', ' ', ucfirst($type));
            $lastCol = $headers ? \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers)) : 'A';
            $sheet->setCellValue('A1', $title);
            $sheet->mergeCells('A1:' . $lastCol . '1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            foreach ($headers as $colIndex => $header) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, 2, $header);
            }

            $rowIndex = 3;
            foreach ($rows as $row) {
                $colIndex = 1;
                foreach ($headers as $header) {
                    $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $row[$header] ?? '');
                    $colIndex++;
                }
                $rowIndex++;
            }

            $headerRange = 'A2:' . $lastCol . '2';
            $dataRange = 'A2:' . $lastCol . ($rowIndex - 1);
            $sheet->getStyle($headerRange)->getFont()->setBold(true);
            $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->freezePane('A3');
            $sheet->setAutoFilter($headerRange);

            for ($i = 1; $i <= count($headers); $i++) {
                $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
            }

            foreach ($headers as $idx => $header) {
                $needle = ['Jumlah', 'Nilai', 'Total'];
                foreach ($needle as $n) {
                    if (stripos($header, $n) !== false) {
                        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx + 1);
                        $sheet->getStyle($colLetter . '3:' . $colLetter . ($rowIndex - 1))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        break;
                    }
                }
            }

            if (in_array($type, ['kas-anggota', 'pemasukan', 'pengeluaran'], true) && $rows->count() > 0) {
                $sumColIndex = null;
                foreach ($headers as $idx => $h) {
                    if (stripos($h, 'Jumlah') !== false) {
                        $sumColIndex = $idx + 1;
                        break;
                    }
                }
                if ($sumColIndex) {
                    $sumColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($sumColIndex);
                    $sheet->setCellValue('A' . $rowIndex, 'Total');
                    $sheet->setCellValue($sumColLetter . $rowIndex, '=SUM(' . $sumColLetter . '3:' . $sumColLetter . ($rowIndex - 1) . ')');
                    $sheet->getStyle('A' . $rowIndex . ':' . $lastCol . $rowIndex)->getFont()->setBold(true);
                }
            }

            $filename = $filenameBase . ($zipAvailable ? '.xlsx' : '.xls');
            $writer = $zipAvailable
                ? new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet)
                : new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
            $contentType = $zipAvailable ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'application/vnd.ms-excel';

            return new StreamedResponse(function () use ($writer) {
                $writer->save('php://output');
            }, 200, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'attachment;filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]);
        }

        $headersArr = array_keys($rows->first() ?? []);
        $filename = $filenameBase . '.csv';

        return new StreamedResponse(function () use ($rows, $headersArr) {
            $out = fopen('php://output', 'w');
            if (!empty($headersArr)) {
                fputcsv($out, $headersArr);
            }
            foreach ($rows as $row) {
                $line = [];
                foreach ($headersArr as $h) {
                    $line[] = $row[$h] ?? '';
                }
                fputcsv($out, $line);
            }
            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment;filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // ==================== KAS SETTINGS METHODS ====================
    
    /**
     * Menampilkan halaman pengaturan kas
     */
    public function kasSettingsIndex()
    {
        try {
            $kasSettings = KasSetting::all()->keyBy('key');
            
            return view('bendahara.kas-settings.index', compact('kasSettings'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat pengaturan kas: ' . $e->getMessage());
        }
    }
    
    /**
     * Update pengaturan kas
     */
    public function kasSettingsUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'jumlah_kas_anggota' => 'required|numeric|min:1000|max:100000',
            ]);
    
            KasSetting::setJumlahKasAnggota($validated['jumlah_kas_anggota']);
    
            return back()->with('success', 'Pengaturan kas berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui pengaturan: ' . $e->getMessage());
        }
    }
}
