<?php

namespace App\Http\Controllers\KoordinatorRedaksi;

use App\Http\Controllers\Controller;
use App\Models\Penjadwalan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controller untuk Manajemen Penjadwalan Koordinator Redaksi
 * 
 * Controller ini menangani semua request yang terkait dengan penjadwalan
 * anggota redaksi untuk membuat berita. Mengikuti prinsip Single Responsibility
 * dengan delegasi logika bisnis ke Service layer.
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
        $data = app(\App\Services\KoordinatorRedaksi\PenjadwalanService::class)->index($request);
        return view('koordinator-redaksi.penjadwalan.index', $data);
    }

    /**
     * Menyimpan penjadwalan baru
     * 
     * @param Request $request Request dari form
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        return app(\App\Services\KoordinatorRedaksi\PenjadwalanService::class)->store($request);
    }

    /**
     * Menampilkan form untuk mengedit penjadwalan
     * 
     * @param int $id ID penjadwalan
     * @return View
     */
    public function edit(int $id): View
    {
        $data = app(\App\Services\KoordinatorRedaksi\PenjadwalanService::class)->edit($id);
        return view('koordinator-redaksi.penjadwalan.edit', $data);
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
        return app(\App\Services\KoordinatorRedaksi\PenjadwalanService::class)->update($request, $id);
    }

    /**
     * Menghapus penjadwalan
     * 
     * @param int $id ID penjadwalan
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorRedaksi\PenjadwalanService::class)->destroy($id);
    }
}

