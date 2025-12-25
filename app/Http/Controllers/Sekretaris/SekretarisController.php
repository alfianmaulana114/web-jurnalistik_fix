<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Notulensi;
use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\Sekretaris\NotulensiService;
use App\Services\Sekretaris\DashboardService;
use App\Services\KoordinatorJurnalistik\ProkerService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\KasAnggota;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\KasSetting;

class SekretarisController extends Controller
{
    /**
     * Service untuk mengelola Notulensi rapat.
     */
    private NotulensiService $notulensiService;

    /**
     * Service untuk data ringkasan pada dashboard Sekretaris.
     */
    private DashboardService $dashboardService;

    /**
     * Service untuk mengelola Program Kerja (Proker).
     */
    private ProkerService $prokerService;

    /**
     * Inisialisasi dependency untuk SekretarisController.
     *
     * Melakukan dependency injection untuk service Notulensi, Dashboard,
     * dan Proker agar logika bisnis tetap berada pada layer service
     * sesuai prinsip Single Responsibility.
     */
    public function __construct(
        NotulensiService $notulensiService,
        DashboardService $dashboardService,
        ProkerService $prokerService
    ) {
        $this->notulensiService = $notulensiService;
        $this->dashboardService = $dashboardService;
        $this->prokerService = $prokerService;
    }

    // Middleware sudah di-handle di route level dengan 'role:sekretaris'

    /**
     * Menampilkan dashboard Sekretaris.
     *
     * @return View
     */
    public function dashboard(): View
    {
        $data = $this->dashboardService->getDashboardData();
        return view('sekretaris.dashboard', $data);
    }

    /**
     * Menampilkan riwayat kas anggota (read-only) mengikuti Bendahara
     */
    public function kasAnggotaRiwayat(Request $request): View
    {
        $data = app(\App\Services\Sekretaris\KasAnggotaService::class)->getRiwayat($request->all());
        return view('sekretaris.kas-anggota.riwayat', $data);
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
        $data = app(\App\Services\Sekretaris\LaporanService::class)->getLaporanKeuanganData($filters);
        return view('sekretaris.laporan.index', $data);
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
            $data = app(\App\Services\Sekretaris\LaporanService::class)->getLaporanKeuanganData($filters);
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

        // Fallback CSV
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

    /**
     * Menampilkan daftar notulensi rapat.
     *
     * @return View
     */
    public function notulensiIndex(): View
    {
        $data = $this->notulensiService->index();
        return view('sekretaris.notulensi.index', $data);
    }

    /**
     * Menampilkan form pembuatan notulensi baru.
     *
     * @return View
     */
    public function notulensiCreate(): View
    {
        $data = $this->notulensiService->create();
        return view('sekretaris.notulensi.create', $data);
    }

    /**
     * Menyimpan notulensi rapat baru.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function notulensiStore(Request $request): RedirectResponse
    {
        return $this->notulensiService->store($request);
    }

    /**
     * Menampilkan detail notulensi rapat.
     *
     * @param Notulensi $notulensi
     * @return View
     */
    public function notulensiShow(Notulensi $notulensi): View
    {
        $data = $this->notulensiService->show($notulensi);
        return view('sekretaris.notulensi.show', $data);
    }

    /**
     * Menampilkan form edit notulensi.
     *
     * @param Notulensi $notulensi
     * @return View
     */
    public function notulensiEdit(Notulensi $notulensi): View
    {
        $data = $this->notulensiService->edit($notulensi);
        return view('sekretaris.notulensi.edit', $data);
    }

    /**
     * Memperbarui data notulensi.
     *
     * @param Request $request
     * @param Notulensi $notulensi
     * @return RedirectResponse
     */
    public function notulensiUpdate(Request $request, Notulensi $notulensi): RedirectResponse
    {
        return $this->notulensiService->update($request, $notulensi);
    }

    /**
     * Menghapus notulensi.
     *
     * @param Notulensi $notulensi
     * @return RedirectResponse
     */
    public function notulensiDestroy(Notulensi $notulensi): RedirectResponse
    {
        return $this->notulensiService->destroy($notulensi);
    }

    public function notulensiDownload(Notulensi $notulensi)
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
     * Menampilkan daftar Program Kerja (Proker).
     *
     * @return View
     */
    public function prokerIndex(): View
    {
        $data = $this->prokerService->index();
        return view('sekretaris.proker.index', $data);
    }

    /**
     * Menampilkan form pembuatan Proker.
     *
     * @return View
     */
    public function prokerCreate(): View
    {
        $data = $this->prokerService->create();
        return view('sekretaris.proker.create', $data);
    }

    /**
     * Menyimpan Proker baru.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function prokerStore(Request $request): RedirectResponse
    {
        return $this->prokerService->store($request);
    }

    /**
     * Menampilkan detail Proker.
     *
     * @param int $id
     * @return View
     */
    public function prokerShow($id): View
    {
        $data = $this->prokerService->show((int)$id);
        return view('sekretaris.proker.show', $data);
    }

    /**
     * Menampilkan form edit Proker.
     *
     * @param int $id
     * @return View
     */
    public function prokerEdit($id): View
    {
        $data = $this->prokerService->edit((int)$id);
        return view('sekretaris.proker.edit', $data);
    }

    /**
     * Memperbarui data Proker.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function prokerUpdate(Request $request, $id): RedirectResponse
    {
        return $this->prokerService->update($request, (int)$id);
    }

    /**
     * Menghapus Proker.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function prokerDestroy($id): RedirectResponse
    {
        return $this->prokerService->destroy((int)$id);
    }
}

