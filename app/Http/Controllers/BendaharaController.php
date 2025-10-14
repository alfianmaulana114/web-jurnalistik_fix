<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KasAnggota;
use App\Models\KasSetting;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Carbon\Carbon;

/**
 * Controller untuk mengelola fungsi-fungsi bendahara UKM Jurnalistik
 * 
 * Fitur yang tersedia:
 * - Dashboard keuangan lengkap
 * - Manajemen kas anggota (CRUD)
 * - Manajemen pemasukan (CRUD)
 * - Manajemen pengeluaran (CRUD)
 * - Laporan keuangan
 */
class BendaharaController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(function ($request, $next) {
                if (!auth()->user()->isBendahara()) {
                    abort(403, 'Unauthorized. Only bendahara can access this page.');
                }
                return $next($request);
            }),
        ];
    }

    /**
     * Menampilkan dashboard bendahara dengan overview keuangan lengkap
     */
    public function dashboard()
    {
        try {
            // Statistik kas anggota
            $totalAnggota = User::whereNotIn('role', [User::ROLE_KOORDINATOR_JURNALISTIK])->count();
            $kasLunas = KasAnggota::where('status_pembayaran', KasAnggota::STATUS_LUNAS)->count();
            $kasTerlambat = KasAnggota::terlambat()->count();
            $kasBelumLunas = KasAnggota::belumLunas()->count();

            // Prepare kas stats array for the view
            $kasStats = [
                'lunas' => $kasLunas,
                'belum_lunas' => $kasBelumLunas,
                'nunggak' => $kasTerlambat
            ];

            // Statistik keuangan bulan ini
            $bulanIni = now()->startOfMonth();
            $pemasukanBulanIni = Pemasukan::verified()
                ->whereDate('tanggal_pemasukan', '>=', $bulanIni)
                ->sum('jumlah');
            
            $pengeluaranBulanIni = Pengeluaran::paid()
                ->whereDate('tanggal_pengeluaran', '>=', $bulanIni)
                ->sum('jumlah');

            // Total saldo
            $totalPemasukan = Pemasukan::verified()->sum('jumlah');
            $totalPengeluaran = Pengeluaran::paid()->sum('jumlah');
            $totalSaldo = $totalPemasukan - $totalPengeluaran;

            // Kas anggota yang terlambat (untuk detail)
            $kasAnggotaTerlambat = KasAnggota::with('user')
                ->terlambat()
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Anggota belum bayar (alias untuk kasAnggotaTerlambat untuk kompatibilitas view)
            $anggotaBelumBayar = $kasAnggotaTerlambat;

            // Transaksi pending yang perlu approval
            $pemasukanPending = Pemasukan::pending()->count();
            $pengeluaranPending = Pengeluaran::pending()->count();
            
            // Gabungkan transaksi pending untuk ditampilkan di dashboard
            $pemasukanPendingList = Pemasukan::pending()->orderBy('created_at', 'desc')->limit(5)->get();
            $pengeluaranPendingList = Pengeluaran::pending()->orderBy('created_at', 'desc')->limit(5)->get();
            $pendingTransactions = $pemasukanPendingList->merge($pengeluaranPendingList)->sortByDesc('created_at');

            // Recent transactions (alias untuk pendingTransactions untuk kompatibilitas view)
            $recentTransactions = $pendingTransactions;

            // Data untuk chart pemasukan vs pengeluaran (6 bulan terakhir)
            $chartData = $this->getChartData();

            // Ringkasan per divisi
            $ringkasanPerDivisi = $this->getRingkasanPerDivisi();

            return view('bendahara.dashboard', compact(
                'totalAnggota',
                'kasLunas',
                'kasTerlambat',
                'kasBelumLunas',
                'kasStats',
                'pemasukanBulanIni',
                'pengeluaranBulanIni',
                'totalSaldo',
                'kasAnggotaTerlambat',
                'anggotaBelumBayar',
                'pemasukanPending',
                'pengeluaranPending',
                'pendingTransactions',
                'recentTransactions',
                'chartData',
                'ringkasanPerDivisi'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard: ' . $e->getMessage());
        }
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
                $query->where('status_pembayaran', $request->status);
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
                'lunas' => KasAnggota::where('status_pembayaran', 'lunas')->count(),
                'belum_lunas' => KasAnggota::where('status_pembayaran', 'belum_lunas')->count(),
                'nunggak' => KasAnggota::where('status_pembayaran', 'nunggak')->count(),
                'total_terkumpul' => KasAnggota::where('status_pembayaran', 'lunas')->sum('jumlah_terbayar')
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
            $maxKasAmount = KasSetting::getValue('jumlah_kas_anggota', 15000);
            
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'jumlah_terbayar' => 'required|numeric|min:0|max:' . $maxKasAmount,
                'periode' => 'required|in:' . implode(',', array_keys(KasAnggota::getAllPeriode())),
                'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
                'keterangan' => 'nullable|string|max:1000',
            ]);

            // Cek apakah sudah ada kas untuk user, periode, dan tahun yang sama
            $existing = KasAnggota::where('user_id', $validated['user_id'])
                                 ->where('periode', $validated['periode'])
                                 ->where('tahun', $validated['tahun'])
                                 ->first();

            if ($existing) {
                return back()->withErrors(['user_id' => 'Kas untuk anggota ini pada periode dan tahun yang sama sudah ada.'])
                           ->withInput();
            }

            $validated['created_by'] = Auth::id();

            $kasAnggota = KasAnggota::create($validated);
            $kasAnggota->updateStatusPembayaran();

            return redirect()->route('bendahara.kas-anggota.index')
                           ->with('success', 'Data kas anggota berhasil ditambahkan.');
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

            return view('bendahara.kas-anggota.edit', compact('kasAnggota', 'users', 'periodeOptions'));
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
            $maxKasAmount = KasSetting::getValue('jumlah_kas_anggota', 15000);
            
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'jumlah_terbayar' => 'required|numeric|min:0|max:' . $maxKasAmount,
                'periode' => 'required|in:' . implode(',', array_keys(KasAnggota::getAllPeriode())),
                'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
                'keterangan' => 'nullable|string|max:1000',
            ]);

            // Cek apakah ada perubahan user_id, periode, atau tahun
            if ($kasAnggota->user_id != $validated['user_id'] || 
                $kasAnggota->periode != $validated['periode'] || 
                $kasAnggota->tahun != $validated['tahun']) {
                
                $existing = KasAnggota::where('user_id', $validated['user_id'])
                                     ->where('periode', $validated['periode'])
                                     ->where('tahun', $validated['tahun'])
                                     ->where('id', '!=', $kasAnggota->id)
                                     ->first();

                if ($existing) {
                    return back()->withErrors(['user_id' => 'Kas untuk anggota ini pada periode dan tahun yang sama sudah ada.'])
                               ->withInput();
                }
            }

            $validated['updated_by'] = Auth::id();
            
            $kasAnggota->update($validated);
            $kasAnggota->updateStatusPembayaran();

            return redirect()->route('bendahara.kas-anggota.index')
                           ->with('success', 'Data kas anggota berhasil diperbarui.');
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
            // Cek apakah ada pemasukan terkait
            if ($kasAnggota->pemasukan()->exists()) {
                return back()->with('error', 'Tidak dapat menghapus kas anggota yang sudah memiliki riwayat pembayaran.');
            }

            $kasAnggota->delete();

            return redirect()->route('bendahara.kas-anggota.index')
                           ->with('success', 'Data kas anggota berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
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
     * Menampilkan daftar pemasukan
     */
    public function pemasukanIndex(Request $request)
    {
        try {
            $query = Pemasukan::with(['kasAnggota.user', 'creator', 'verifier']);

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan kategori
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Filter berdasarkan tanggal
            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_pemasukan', '>=', $request->tanggal_mulai);
            }
            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('tanggal_pemasukan', '<=', $request->tanggal_selesai);
            }

            // Search berdasarkan kode transaksi atau sumber
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('kode_transaksi', 'like', '%' . $request->search . '%')
                      ->orWhere('sumber_pemasukan', 'like', '%' . $request->search . '%');
                });
            }

            $pemasukan = $query->orderBy('created_at', 'desc')->paginate(15);

            // Data untuk filter
            $kategoriOptions = Pemasukan::getAllKategori();
            $statusOptions = Pemasukan::getAllStatus();

            return view('bendahara.pemasukan.index', compact(
                'pemasukan',
                'kategoriOptions',
                'statusOptions'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat data pemasukan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk membuat pemasukan baru
     */
    public function pemasukanCreate()
    {
        try {
            $kasAnggota = KasAnggota::with('user')->belumLunas()->get();
            $kategoriOptions = Pemasukan::getAllKategori();
            $metodePembayaranOptions = Pemasukan::getAllMetodePembayaran();

            return view('bendahara.pemasukan.create', compact(
                'kasAnggota',
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
            $validated = $request->validate([
                'sumber_pemasukan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:1000',
                'jumlah' => 'required|numeric|min:0',
                'tanggal_pemasukan' => 'required|date',
                'kategori' => 'required|in:' . implode(',', array_keys(Pemasukan::getAllKategori())),
                'metode_pembayaran' => 'required|in:' . implode(',', array_keys(Pemasukan::getAllMetodePembayaran())),
                'nomor_referensi' => 'nullable|string|max:100',
                'kas_anggota_id' => 'nullable|exists:kas_anggota,id',
                'bukti_pemasukan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'keterangan' => 'nullable|string|max:1000',
            ]);

            $validated['user_id'] = Auth::id();
            $validated['created_by'] = Auth::id();

            // Upload bukti pemasukan jika ada
            if ($request->hasFile('bukti_pemasukan')) {
                $file = $request->file('bukti_pemasukan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('bukti_pemasukan', $filename, 'public');
                $validated['bukti_pemasukan'] = $path;
            }

            $pemasukan = Pemasukan::create($validated);

            // Update kas anggota jika terkait pembayaran kas
            if ($pemasukan->kas_anggota_id && $pemasukan->kategori === 'kas_anggota') {
                $kasAnggota = KasAnggota::find($pemasukan->kas_anggota_id);
                $kasAnggota->jumlah_terbayar += $pemasukan->jumlah;
                $kasAnggota->tanggal_pembayaran = $pemasukan->tanggal_pemasukan;
                $kasAnggota->updateStatusPembayaran();
                $kasAnggota->save();
            }

            return redirect()->route('bendahara.pemasukan.index')
                           ->with('success', 'Data pemasukan berhasil ditambahkan.');
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
            $validated = $request->validate([
                'sumber_pemasukan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:1000',
                'jumlah' => 'required|numeric|min:0',
                'tanggal_pemasukan' => 'required|date',
                'kategori' => 'required|in:' . implode(',', array_keys(Pemasukan::getAllKategori())),
                'metode_pembayaran' => 'required|in:' . implode(',', array_keys(Pemasukan::getAllMetodePembayaran())),
                'nomor_referensi' => 'nullable|string|max:100',
                'kas_anggota_id' => 'nullable|exists:kas_anggota,id',
                'bukti_pemasukan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'keterangan' => 'nullable|string|max:1000',
            ]);

            // Upload bukti pemasukan baru jika ada
            if ($request->hasFile('bukti_pemasukan')) {
                // Hapus file lama jika ada
                if ($pemasukan->bukti_pemasukan) {
                    Storage::disk('public')->delete($pemasukan->bukti_pemasukan);
                }

                $file = $request->file('bukti_pemasukan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('bukti_pemasukan', $filename, 'public');
                $validated['bukti_pemasukan'] = $path;
            }

            $pemasukan->update($validated);

            return redirect()->route('bendahara.pemasukan.index')
                           ->with('success', 'Data pemasukan berhasil diperbarui.');
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
            // Hapus file bukti jika ada
            if ($pemasukan->bukti_pemasukan) {
                Storage::disk('public')->delete($pemasukan->bukti_pemasukan);
            }

            // Update kas anggota jika terkait
            if ($pemasukan->kas_anggota_id && $pemasukan->kategori === 'kas_anggota') {
                $kasAnggota = KasAnggota::find($pemasukan->kas_anggota_id);
                $kasAnggota->jumlah_terbayar -= $pemasukan->jumlah;
                $kasAnggota->updateStatusPembayaran();
                $kasAnggota->save();
            }

            $pemasukan->delete();

            return redirect()->route('bendahara.pemasukan.index')
                           ->with('success', 'Data pemasukan berhasil dihapus.');
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
            if ($pemasukan->isVerified()) {
                return back()->with('error', 'Pemasukan sudah diverifikasi sebelumnya.');
            }

            $pemasukan->update([
                'status' => Pemasukan::STATUS_VERIFIED,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            return back()->with('success', 'Pemasukan berhasil diverifikasi.');
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
            $query = Pengeluaran::with(['creator', 'approver', 'payer']);

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan kategori
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Filter berdasarkan tanggal
            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_pengeluaran', '>=', $request->tanggal_mulai);
            }
            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('tanggal_pengeluaran', '<=', $request->tanggal_selesai);
            }

            // Search berdasarkan kode transaksi atau keperluan
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('kode_transaksi', 'like', '%' . $request->search . '%')
                      ->orWhere('keperluan', 'like', '%' . $request->search . '%');
                });
            }

            $pengeluaran = $query->orderBy('created_at', 'desc')->paginate(15);

            // Data untuk filter
            $kategoriOptions = Pengeluaran::getAllKategori();
            $statusOptions = Pengeluaran::getAllStatus();

            return view('bendahara.pengeluaran.index', compact(
                'pengeluaran',
                'kategoriOptions',
                'statusOptions'
            ));
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
            $validated = $request->validate([
                'keperluan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:1000',
                'jumlah' => 'required|numeric|min:0',
                'tanggal_pengeluaran' => 'required|date',
                'kategori' => 'required|in:' . implode(',', array_keys(Pengeluaran::getAllKategori())),
                'metode_pembayaran' => 'required|in:' . implode(',', array_keys(Pengeluaran::getAllMetodePembayaran())),
                'nomor_referensi' => 'nullable|string|max:100',
                'penerima' => 'required|string|max:255',
                'bukti_pengeluaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'keterangan' => 'nullable|string|max:1000',
            ]);

            $validated['created_by'] = Auth::id();

            // Upload bukti pengeluaran jika ada
            if ($request->hasFile('bukti_pengeluaran')) {
                $file = $request->file('bukti_pengeluaran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('bukti_pengeluaran', $filename, 'public');
                $validated['bukti_pengeluaran'] = $path;
            }

            Pengeluaran::create($validated);

            return redirect()->route('bendahara.pengeluaran.index')
                           ->with('success', 'Data pengeluaran berhasil ditambahkan.');
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
            $validated = $request->validate([
                'keperluan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:1000',
                'jumlah' => 'required|numeric|min:0',
                'tanggal_pengeluaran' => 'required|date',
                'kategori' => 'required|in:' . implode(',', array_keys(Pengeluaran::getAllKategori())),
                'metode_pembayaran' => 'required|in:' . implode(',', array_keys(Pengeluaran::getAllMetodePembayaran())),
                'nomor_referensi' => 'nullable|string|max:100',
                'penerima' => 'required|string|max:255',
                'bukti_pengeluaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'keterangan' => 'nullable|string|max:1000',
            ]);

            // Upload bukti pengeluaran baru jika ada
            if ($request->hasFile('bukti_pengeluaran')) {
                // Hapus file lama jika ada
                if ($pengeluaran->bukti_pengeluaran) {
                    Storage::disk('public')->delete($pengeluaran->bukti_pengeluaran);
                }

                $file = $request->file('bukti_pengeluaran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('bukti_pengeluaran', $filename, 'public');
                $validated['bukti_pengeluaran'] = $path;
            }

            $pengeluaran->update($validated);

            return redirect()->route('bendahara.pengeluaran.index')
                           ->with('success', 'Data pengeluaran berhasil diperbarui.');
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
            // Hapus file bukti jika ada
            if ($pengeluaran->bukti_pengeluaran) {
                Storage::disk('public')->delete($pengeluaran->bukti_pengeluaran);
            }

            $pengeluaran->delete();

            return redirect()->route('bendahara.pengeluaran.index')
                           ->with('success', 'Data pengeluaran berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Approve pengeluaran
     */
    public function pengeluaranApprove(Pengeluaran $pengeluaran)
    {
        try {
            if ($pengeluaran->isApproved()) {
                return back()->with('error', 'Pengeluaran sudah disetujui sebelumnya.');
            }

            $pengeluaran->update([
                'status' => Pengeluaran::STATUS_APPROVED,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return back()->with('success', 'Pengeluaran berhasil disetujui.');
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
            if (!$pengeluaran->isApproved()) {
                return back()->with('error', 'Pengeluaran harus disetujui terlebih dahulu.');
            }

            if ($pengeluaran->isPaid()) {
                return back()->with('error', 'Pengeluaran sudah dibayar sebelumnya.');
            }

            $pengeluaran->update([
                'status' => Pengeluaran::STATUS_PAID,
                'paid_by' => Auth::id(),
                'paid_at' => now(),
            ]);

            return back()->with('success', 'Pengeluaran berhasil ditandai sebagai dibayar.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
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