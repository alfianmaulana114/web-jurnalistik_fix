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

