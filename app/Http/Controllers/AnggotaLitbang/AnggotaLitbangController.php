<?php

namespace App\Http\Controllers\AnggotaLitbang;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AnggotaLitbangController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Anggota Litbang.
     *
     * Mengambil data ringkasan aktivitas, brief yang ditugaskan,
     * dan informasi relevan lainnya melalui `DashboardService`.
     *
     * @return View
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\AnggotaLitbang\DashboardService::class)->getDashboardData();
        return view('anggota-litbang.dashboard', $data);
    }
}