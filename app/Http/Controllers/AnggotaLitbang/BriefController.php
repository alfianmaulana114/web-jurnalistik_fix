<?php

namespace App\Http\Controllers\AnggotaLitbang;

use App\Http\Controllers\Controller;
use App\Models\Brief;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BriefController extends Controller
{
    /**
     * Service untuk mengelola Brief oleh Anggota Litbang.
     */
    private \App\Services\AnggotaLitbang\BriefService $briefService;

    /**
     * Inisialisasi dependency BriefService.
     */
    public function __construct(\App\Services\AnggotaLitbang\BriefService $briefService)
    {
        $this->briefService = $briefService;
    }

    /**
     * Menampilkan daftar brief.
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->briefService->index();
        return view('anggota-litbang.briefs.index', $data);
    }

    /**
     * Menampilkan form pembuatan brief baru.
     *
     * @return View
     */
    public function create(): View
    {
        $data = $this->briefService->create();
        return view('anggota-litbang.briefs.create', $data);
    }

    /**
     * Menyimpan brief baru.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->briefService->store($request);
    }

    /**
     * Menampilkan detail brief.
     *
     * @param Brief $brief
     * @return View
     */
    public function show(Brief $brief): View
    {
        $data = $this->briefService->show($brief);
        return view('anggota-litbang.briefs.show', $data);
    }

    /**
     * Menampilkan form edit brief.
     *
     * @param Brief $brief
     * @return View
     */
    public function edit(Brief $brief): View
    {
        $data = $this->briefService->edit($brief);
        return view('anggota-litbang.briefs.edit', $data);
    }

    /**
     * Memperbarui data brief.
     *
     * @param Request $request
     * @param Brief $brief
     * @return RedirectResponse
     */
    public function update(Request $request, Brief $brief): RedirectResponse
    {
        return $this->briefService->update($request, $brief);
    }

    /**
     * Menghapus brief.
     *
     * @param Brief $brief
     * @return RedirectResponse
     */
    public function destroy(Brief $brief): RedirectResponse
    {
        return $this->briefService->destroy($brief);
    }
}