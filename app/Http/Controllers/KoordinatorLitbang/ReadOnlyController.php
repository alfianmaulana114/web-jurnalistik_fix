<?php

namespace App\Http\Controllers\KoordinatorLitbang;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Proker;
use App\Models\Content;
use App\Models\Design;
use App\Models\User;
use App\Models\Funfact;
use App\Models\BriefHumas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Notulensi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\KasAnggota;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\KasSetting;

/**
 * Controller untuk Koordinator Litbang - Read Only Access
 * 
 * Controller ini memberikan akses read-only untuk koordinator litbang ke semua fitur
 * yang sama seperti bendahara (read-only). Koordinator litbang hanya bisa melihat data
 * tanpa bisa melakukan CRUD operations.
 */
class ReadOnlyController extends Controller
{
    /**
     * Menampilkan dashboard koordinator jurnalistik (read-only)
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\DashboardService::class)->getDashboardData();
        return view('koordinator-litbang.read-only.dashboard', $data);
    }

    /**
     * Menampilkan daftar berita (read-only)
     */
    public function newsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->index($request);
        return view('koordinator-litbang.read-only.news.index', $data);
    }

    /**
     * Menampilkan detail berita (read-only)
     */
    public function newsShow($id): View
    {
        $news = News::findOrFail($id);
        return view('koordinator-litbang.read-only.news.show', compact('news'));
    }

    /**
     * Menampilkan daftar proker (read-only)
     */
    public function prokersIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\ProkerService::class)->index($request);
        return view('koordinator-litbang.read-only.prokers.index', $data);
    }

    /**
     * Menampilkan detail proker (read-only)
     */
    public function prokersShow(Proker $proker): View
    {
        $proker->load(['creator', 'panitias']);
        return view('koordinator-litbang.read-only.prokers.show', compact('proker'));
    }

    /**
     * Menampilkan daftar content (read-only)
     */
    public function contentsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\ContentService::class)->index($request);
        return view('koordinator-litbang.read-only.contents.index', $data);
    }

    /**
     * Menampilkan detail content (read-only)
     */
    public function contentsShow(Content $content): View
    {
        $content->load(['brief', 'creator', 'berita', 'desain']);
        return view('koordinator-litbang.read-only.contents.show', compact('content'));
    }

    /**
     * Menampilkan daftar design (read-only)
     */
    public function designsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\DesignService::class)->index($request);
        return view('koordinator-litbang.read-only.designs.index', $data);
    }

    /**
     * Menampilkan detail design (read-only)
     */
    public function designsShow(Design $design): View
    {
        return view('koordinator-litbang.read-only.designs.show', compact('design'));
    }

    /**
     * Menampilkan daftar funfact (read-only)
     */
    public function funfactsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\FunfactService::class)->index($request);
        return view('koordinator-litbang.read-only.funfacts.index', $data);
    }

    /**
     * Menampilkan detail funfact (read-only)
     */
    public function funfactsShow(Funfact $funfact): View
    {
        return view('koordinator-litbang.read-only.funfacts.show', compact('funfact'));
    }

    /**
     * Menampilkan daftar user (read-only)
     */
    public function usersIndex(Request $request): View
    {
        $query = User::query();

        // Filter by search (name, email, atau NIM)
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            if (strlen($search) > 255) {
                $search = substr($search, 0, 255);
            }
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $allowedRoles = array_keys(User::getAllRoles());
            if (in_array($request->role, $allowedRoles)) {
                $query->where('role', $request->role);
            }
        }

        // Filter by divisi
        if ($request->has('divisi') && $request->divisi) {
            $divisi = $request->divisi;
            $query->where(function($q) use ($divisi) {
                if ($divisi === 'redaksi') {
                    $q->whereIn('role', ['koordinator_redaksi', 'anggota_redaksi']);
                } elseif ($divisi === 'litbang') {
                    $q->whereIn('role', ['koordinator_litbang', 'anggota_litbang']);
                } elseif ($divisi === 'humas') {
                    $q->whereIn('role', ['koordinator_humas', 'anggota_humas']);
                } elseif ($divisi === 'media_kreatif') {
                    $q->whereIn('role', ['koordinator_media_kreatif', 'anggota_media_kreatif']);
                } elseif ($divisi === 'pengurus') {
                    $q->whereIn('role', ['sekretaris', 'bendahara']);
                }
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        return view('koordinator-litbang.read-only.users.index', compact('users'));
    }

    /**
     * Menampilkan detail user (read-only)
     */
    public function usersShow(User $user): View
    {
        return view('koordinator-litbang.read-only.users.show', compact('user'));
    }

    public function briefHumasIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefHumasService::class)->index($request);
        return view('koordinator-litbang.read-only.brief-humas.index', $data);
    }

    public function briefHumasShow(BriefHumas $briefHumas): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefHumasService::class)->show($briefHumas);
        return view('koordinator-litbang.read-only.brief-humas.show', $data);
    }

    // Sekretaris (Read-Only)
    public function sekretarisNotulensiIndex(): View
    {
        $data = app(\App\Services\Sekretaris\NotulensiService::class)->index();
        return view('koordinator-litbang.read-only.sekretaris.notulensi.index', $data);
    }

    public function sekretarisNotulensiShow(Notulensi $notulensi): View
    {
        $data = app(\App\Services\Sekretaris\NotulensiService::class)->show($notulensi);
        return view('koordinator-litbang.read-only.sekretaris.notulensi.show', $data);
    }

    public function sekretarisAbsenIndex(Request $request): View
    {
        $data = app(\App\Services\Sekretaris\AbsenService::class)->index($request->all());
        return view('koordinator-litbang.read-only.sekretaris.absen.index', $data);
    }

    public function sekretarisNotulensiDownload(Notulensi $notulensi)
    {
        if (empty($notulensi->pdf_path)) {
            return back()->with('error', 'PDF tidak tersedia untuk notulensi ini.');
        }
        if (!Storage::disk('public')->exists($notulensi->pdf_path)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
        $filename = 'Notulensi-' . Str::slug($notulensi->judul) . '.pdf';
        $fullPath = storage_path('app/public/' . $notulensi->pdf_path);
        return response()->download($fullPath, $filename);
    }

    /**
     * Menampilkan laporan keuangan (read-only)
     */
    public function laporanKeuangan(Request $request): View
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
            return view('koordinator-litbang.read-only.laporan.index', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan keuangan: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel laporan: kas-anggota | pemasukan | pengeluaran | total-saldo
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
            $standardAmount = KasSetting::getJumlahKasAnggota();
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

                if ($rows->isEmpty()) {
                    $sheet->setCellValue('A1', 'Tidak ada data');
                    return;
                }

                $headers = array_keys($rows->first());
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col . '1', $header);
                    $sheet->getStyle($col . '1')->getFont()->setBold(true);
                    $col++;
                }

                $row = 2;
                foreach ($rows as $dataRow) {
                    $col = 'A';
                    foreach ($headers as $header) {
                        $sheet->setCellValue($col . $row, $dataRow[$header] ?? '');
                        $col++;
                    }
                    $row++;
                }

                foreach (range('A', $col) as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            };

            $makeSheet($spreadsheet, 'Data', $rows);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            $filename = $filenameBase . '.xlsx';

            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } else {
            abort(500, 'PHP Spreadsheet library tidak tersedia. Silakan install php-office/phpspreadsheet.');
        }
    }
}

