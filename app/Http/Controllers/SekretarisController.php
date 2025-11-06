<?php

namespace App\Http\Controllers;

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
    private NotulensiService $notulensiService;
    private DashboardService $dashboardService;
    private ProkerService $prokerService;

    public function __construct(
        NotulensiService $notulensiService,
        DashboardService $dashboardService,
        ProkerService $prokerService
    ) {
        $this->notulensiService = $notulensiService;
        $this->dashboardService = $dashboardService;
        $this->prokerService = $prokerService;
    }

    public static function middleware(): array
    {
        return [
            'auth',
            new \Illuminate\Routing\Controllers\Middleware(function ($request, $next) {
                if (!auth()->user()->isSekretaris()) {
                    abort(403, 'Unauthorized. Only sekretaris can access this page.');
                }
                return $next($request);
            }),
        ];
    }

    // Dashboard
    public function dashboard(): View
    {
        $data = $this->dashboardService->getDashboardData();
        return view('sekretaris.dashboard', $data);
    }

    // Notulensi CRUD
    public function notulensiIndex(): View
    {
        $data = $this->notulensiService->index();
        return view('sekretaris.notulensi.index', $data);
    }

    public function notulensiCreate(): View
    {
        $data = $this->notulensiService->create();
        return view('sekretaris.notulensi.create', $data);
    }

    public function notulensiStore(Request $request): RedirectResponse
    {
        return $this->notulensiService->store($request);
    }

    public function notulensiShow(Notulensi $notulensi): View
    {
        $data = $this->notulensiService->show($notulensi);
        return view('sekretaris.notulensi.show', $data);
    }

    public function notulensiEdit(Notulensi $notulensi): View
    {
        $data = $this->notulensiService->edit($notulensi);
        return view('sekretaris.notulensi.edit', $data);
    }

    public function notulensiUpdate(Request $request, Notulensi $notulensi): RedirectResponse
    {
        return $this->notulensiService->update($request, $notulensi);
    }

    public function notulensiDestroy(Notulensi $notulensi): RedirectResponse
    {
        return $this->notulensiService->destroy($notulensi);
    }

    // Proker CRUD (menggunakan ProkerService dari KoordinatorJurnalistik)
    public function prokerIndex(): View
    {
        $data = $this->prokerService->index();
        return view('sekretaris.proker.index', $data);
    }

    public function prokerCreate(): View
    {
        $data = $this->prokerService->create();
        return view('sekretaris.proker.create', $data);
    }

    public function prokerStore(Request $request): RedirectResponse
    {
        return $this->prokerService->store($request);
    }

    public function prokerShow($id): View
    {
        $data = $this->prokerService->show((int)$id);
        return view('sekretaris.proker.show', $data);
    }

    public function prokerEdit($id): View
    {
        $data = $this->prokerService->edit((int)$id);
        return view('sekretaris.proker.edit', $data);
    }

    public function prokerUpdate(Request $request, $id): RedirectResponse
    {
        return $this->prokerService->update($request, (int)$id);
    }

    public function prokerDestroy($id): RedirectResponse
    {
        return $this->prokerService->destroy((int)$id);
    }
}

