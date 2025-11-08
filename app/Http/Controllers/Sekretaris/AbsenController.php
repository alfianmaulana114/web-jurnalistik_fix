<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\Sekretaris\AbsenService;

class AbsenController extends Controller
{
    private AbsenService $absenService;

    public function __construct(AbsenService $absenService)
    {
        $this->absenService = $absenService;
    }

    public function index(Request $request): View
    {
        $data = $this->absenService->index($request->all());
        return view('sekretaris.absen.index', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->absenService->store($request);
    }

    public function update(Request $request, Absen $absen): RedirectResponse
    {
        return $this->absenService->update($request, $absen);
    }

    public function destroy(Absen $absen): RedirectResponse
    {
        return $this->absenService->destroy($absen);
    }

    public function storeBulk(Request $request): RedirectResponse
    {
        return $this->absenService->storeBulk($request);
    }
}

