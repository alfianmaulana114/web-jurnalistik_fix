<?php

namespace App\Http\Controllers\KoordinatorLitbang;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KoordinatorLitbangController extends Controller
{
    /**
     * Menampilkan dashboard Koordinator Litbang.
     *
     * Memuat ringkasan brief, penjadwalan untuk anggota,
     * serta statistik yang relevan melalui `DashboardService`.
     *
     * @return View
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\KoordinatorLitbang\DashboardService::class)->getDashboardData();
        return view('koordinator-litbang.dashboard', $data);
    }
}