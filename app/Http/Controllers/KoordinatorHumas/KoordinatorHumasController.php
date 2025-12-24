<?php

namespace App\Http\Controllers\KoordinatorHumas;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Controller untuk Koordinator Humas
 * 
 * Controller ini menangani semua request yang terkait dengan koordinator humas,
 * termasuk dashboard. Mengikuti prinsip Single Responsibility dengan delegasi
 * logika bisnis ke Service layer.
 */
class KoordinatorHumasController extends Controller
{
    /**
     * Menampilkan dashboard koordinator humas
     * 
     * @return View
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\KoordinatorHumas\DashboardService::class)->getDashboardData();
        return view('koordinator-humas.dashboard', $data);
    }
}

