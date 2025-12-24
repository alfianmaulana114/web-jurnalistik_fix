<?php

namespace App\Http\Controllers\KoordinatorHumas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controller untuk Manajemen Penjadwalan Koordinator Humas
 * 
 * Controller ini menangani semua request yang terkait dengan penjadwalan
 * anggota humas. Mengikuti prinsip Single Responsibility dengan delegasi
 * logika bisnis ke Service layer.
 */
class PenjadwalanController extends Controller
{
    /**
     * Menampilkan halaman penjadwalan dengan kalender
     * 
     * @param Request $request Request yang berisi filter bulan dan tahun
     * @return View
     */
    public function index(Request $request): View
    {
        $data = app(\App\Services\KoordinatorHumas\PenjadwalanService::class)->index($request);
        return view('koordinator-humas.penjadwalan.index', $data);
    }

    /**
     * Menyimpan penjadwalan baru
     * 
     * @param Request $request Request dari form
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        return app(\App\Services\KoordinatorHumas\PenjadwalanService::class)->store($request);
    }

    /**
     * Menampilkan form untuk mengedit penjadwalan
     * 
     * @param int $id ID penjadwalan
     * @return View
     */
    public function edit(int $id): View
    {
        $data = app(\App\Services\KoordinatorHumas\PenjadwalanService::class)->edit($id);
        return view('koordinator-humas.penjadwalan.edit', $data);
    }

    /**
     * Memperbarui penjadwalan yang sudah ada
     * 
     * @param Request $request Request dari form
     * @param int $id ID penjadwalan
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorHumas\PenjadwalanService::class)->update($request, $id);
    }

    /**
     * Menghapus penjadwalan
     * 
     * @param int $id ID penjadwalan
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorHumas\PenjadwalanService::class)->destroy($id);
    }
}

