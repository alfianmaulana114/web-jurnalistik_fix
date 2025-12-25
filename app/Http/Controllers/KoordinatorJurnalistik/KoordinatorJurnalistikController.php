<?php

namespace App\Http\Controllers\KoordinatorJurnalistik;

use App\Http\Controllers\Controller;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsType;
use App\Models\NewsGenre;
use App\Models\TempImage;
use App\Models\User;
use App\Models\Proker;
use App\Models\Brief;
use App\Models\Content;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\NewsImageService;
use App\Services\EditorService;
use App\Services\ImageCropperService;
use Illuminate\Http\JsonResponse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\KasAnggota;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\KasSetting;
use App\Models\Notulensi;

class KoordinatorJurnalistikController extends Controller
{
    private $newsImageService;
    private $editorService;
    private $cropperService;
    private $manager;

    public function __construct(
        NewsImageService $imageService,
        EditorService $editorService,
        ImageCropperService $cropperService
    ) {
        $this->newsImageService = $imageService;
        $this->editorService = $editorService;
        $this->cropperService = $cropperService;
        $this->manager = new ImageManager(new Driver());
    }
    /**
     * Safe count method that handles missing tables
     */
    private function safeCount($modelClass)
    {
        try {
            return $modelClass::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safe query method that handles missing tables
     */
    private function safeQuery($callback, $default = null)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Display the koordinator jurnalistik dashboard.
     * Dashboard lengkap mengenai semua divisi termasuk sekretaris dan bendahara
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\DashboardService::class)->getDashboardData();
        return view('koordinator-jurnalistik.dashboard', $data);
    }

    /**
     * Menampilkan riwayat kas anggota (read-only) mengikuti Bendahara
     */
    public function kasAnggotaRiwayat(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\KasAnggotaService::class)->getRiwayat($request->all());
        return view('koordinator-jurnalistik.kas-anggota.riwayat', $data);
    }

    /**
     * Menampilkan laporan keuangan (read-only) mengikuti Bendahara
     */
    public function laporanKeuangan(Request $request): View
    {
        $filters = [
            'periode' => $request->get('periode', 'bulan_ini'),
            'bulan' => (int) $request->get('bulan', now()->month),
            'tahun' => (int) $request->get('tahun', now()->year),
            'tanggal_mulai' => $request->get('tanggal_mulai'),
            'tanggal_selesai' => $request->get('tanggal_selesai'),
        ];
        $data = app(\App\Services\KoordinatorJurnalistik\LaporanService::class)->getLaporanKeuanganData($filters);
        return view('koordinator-jurnalistik.laporan.index', $data);
    }

    // Sekretaris (Read-Only)
    public function sekretarisNotulensiIndex(): View
    {
        $data = app(\App\Services\Sekretaris\NotulensiService::class)->index();
        return view('koordinator-jurnalistik.read-only.notulensi.index', $data);
    }

    public function sekretarisNotulensiShow(Notulensi $notulensi): View
    {
        $data = app(\App\Services\Sekretaris\NotulensiService::class)->show($notulensi);
        return view('koordinator-jurnalistik.read-only.notulensi.show', $data);
    }

    public function sekretarisAbsenIndex(Request $request): View
    {
        $data = app(\App\Services\Sekretaris\AbsenService::class)->index($request->all());
        return view('koordinator-jurnalistik.read-only.absen.index', $data);
    }

    public function sekretarisNotulensiDownload(Notulensi $notulensi)
    {
        if (empty($notulensi->pdf_path)) {
            return back()->with('error', 'PDF tidak tersedia untuk notulensi ini.');
        }
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($notulensi->pdf_path)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
        $filename = 'Notulensi-' . \Illuminate\Support\Str::slug($notulensi->judul) . '.pdf';
        $fullPath = storage_path('app/public/' . $notulensi->pdf_path);
        return response()->download($fullPath, $filename);
    }

    /**
     * Export Excel laporan keuangan (kas-anggota | pemasukan | pengeluaran | total-saldo)
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

        if ($type === 'kas-anggota') {
            $bulanMap = [
                1 => 'januari', 2 => 'februari', 3 => 'maret', 4 => 'april', 5 => 'mei', 6 => 'juni',
                7 => 'juli', 8 => 'agustus', 9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'desember'
            ];
            $periodeBulan = $bulanMap[$bulan] ?? strtolower(now()->format('F'));
            $standardAmount = KasSetting::getJumlahKasAnggota();
            $rows = KasAnggota::with(['user', 'creator', 'updater'])
                ->where('tahun', $tahun)
                ->where('periode', $periodeBulan)
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
                        'Periode' => ucfirst($item->periode) . ' ' . $item->tahun,
                        'Jumlah Wajib' => (float) $standardAmount,
                        'Jumlah Dibayar' => (float) $item->jumlah_terbayar,
                        'Kekurangan' => (float) $kekurangan,
                        'Status' => $item->getStatusPembayaranLabel(),
                        'Tanggal Bayar' => optional($item->tanggal_pembayaran)->format('Y-m-d'),
                        'Dibuat Oleh' => optional($item->creator)->name,
                        'Diperbarui Oleh' => optional($item->updater)->name,
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
        } else { // total-saldo
            $filters = [
                'periode' => $periode,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
            ];
            $data = app(\App\Services\KoordinatorJurnalistik\LaporanService::class)->getLaporanKeuanganData($filters);
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

            if ($type === 'total-saldo') {
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
                    $sheet->getStyle($headerRange)->getFont()->setBold(true);
                    $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
                    $sheet->freezePane('A3');
                    for ($i = 1; $i <= count($headers); $i++) {
                        $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
                    }
                    return $sheet;
                };

                $summaryRows = $rows;
                $spreadsheet->setActiveSheetIndex(0);
                $makeSheet($spreadsheet, 'Total Saldo', $summaryRows);

                $queryPemasukan = Pemasukan::verified()->with(['creator', 'verifier', 'user']);
                if ($periode === 'custom' && $tanggalMulai && $tanggalSelesai) {
                    $queryPemasukan->whereBetween('tanggal_pemasukan', [$tanggalMulai, $tanggalSelesai]);
                } else {
                    $queryPemasukan->whereMonth('tanggal_pemasukan', $bulan)->whereYear('tanggal_pemasukan', $tahun);
                }
                $pemasukanRows = $queryPemasukan->orderBy('tanggal_pemasukan')->get()->map(function ($item) {
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
                $pengeluaranRows = $queryPengeluaran->orderBy('tanggal_pengeluaran')->get()->map(function ($item) {
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

                $kasRows = KasAnggota::with(['user', 'creator', 'updater'])
                    ->whereYear('tanggal_pembayaran', $tahun)
                    ->whereMonth('tanggal_pembayaran', $bulan)
                    ->orderBy('user_id')
                    ->get()
                    ->map(function ($item) {
                        $user = optional($item->user);
                        $divisi = method_exists($user, 'getDivision') ? ucfirst(str_replace('_',' ', $user->getDivision())) : '';
                        return [
                            'Nama' => $user->name,
                            'NIM' => $user->nim,
                            'Divisi' => $divisi,
                            'Periode' => ucfirst($item->periode) . ' ' . $item->tahun,
                            'Jumlah Dibayar' => (float) $item->jumlah_terbayar,
                            'Status' => $item->getStatusPembayaranLabel(),
                            'Tanggal Bayar' => optional($item->tanggal_pembayaran)->format('Y-m-d'),
                            'Dibuat Oleh' => optional($item->creator)->name,
                            'Diperbarui Oleh' => optional($item->updater)->name,
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
                $needle = ['Jumlah', 'Nilai', 'Total', 'Kekurangan'];
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

        // Fallback sederhana: CSV
        $headers = array_keys($rows->first() ?? []);
        $filename = $filenameBase . '.csv';
        return new StreamedResponse(function () use ($rows, $headers) {
            $out = fopen('php://output', 'w');
            if ($headers) fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, array_map(function ($h) use ($row) { return $row[$h] ?? ''; }, $headers));
            }
            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment;filename="' . $filename . '"',
        ]);
    }

    // News Management Methods
    /**
     * Menampilkan daftar semua berita untuk Koordinator Jurnalistik.
     *
     * @param Request $request
     * @return View
     */
    public function newsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->index($request);
        return view('koordinator-jurnalistik.news.index', $data);
    }

    /**
     * Menampilkan form pembuatan berita baru.
     *
     * Memuat resource tambahan untuk editor teks dan cropper gambar.
     *
     * @return View
     */
    public function newsCreate(): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->create();
        return view('koordinator-jurnalistik.news.create', $data + [
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts(),
        ]);
    }

    /**
     * Menyimpan berita baru.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function newsStore(Request $request): RedirectResponse
    {
        return app(\App\Services\KoordinatorJurnalistik\NewsService::class)->store($request);
    }

    /**
     * Menampilkan detail berita.
     *
     * @param int $id
     * @return View
     */
    public function newsShow($id): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->show($id);
        return view('koordinator-jurnalistik.news.show', $data);
    }

    /**
     * Menampilkan form edit berita.
     *
     * Memuat resource editor dan cropper untuk pengeditan.
     *
     * @param int $id
     * @return View
     */
    public function newsEdit($id): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->edit($id);
        return view('koordinator-jurnalistik.news.edit', $data + [
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts(),
        ]);
    }

    /**
     * Memperbarui data berita.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function newsUpdate(Request $request, $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorJurnalistik\NewsService::class)->update($request, (int) $id);
    }

    /**
     * Menghapus berita.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function newsDestroy($id): RedirectResponse
    {
        return app(\App\Services\KoordinatorJurnalistik\NewsService::class)->destroy((int) $id);
    }

    /**
     * Mengunggah gambar sementara untuk konten berita.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $path = $this->newsImageService->store($request->file('image'));
            return response()->json(asset($path));
        }
        return response()->json('Upload failed', 400);
    }

    /**
     * Validasi input berita.
     *
     * @param Request $request
     * @param int|null $id
     * @return array
     */
    private function validateNews(Request $request, $id = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_description' => 'required|string|max:160',
            'tags' => 'required|string',
            'keyword' => 'nullable|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'news_type_id' => 'required|exists:news_types,id',
            'genre_ids' => 'required|array|exists:news_genres,id'
        ];
    
        return $request->validate($rules);
    }
}
