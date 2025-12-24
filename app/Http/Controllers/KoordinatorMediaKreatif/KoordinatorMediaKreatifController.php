<?php

namespace App\Http\Controllers\KoordinatorMediaKreatif;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk Koordinator Media Kreatif
 * 
 * Controller ini menangani semua request yang terkait dengan koordinator media kreatif,
 * termasuk dashboard. Mengikuti prinsip Single Responsibility dengan delegasi logika
 * bisnis ke Service layer.
 */
class KoordinatorMediaKreatifController extends Controller
{
    /**
     * Menampilkan dashboard koordinator media kreatif
     * 
     * @return View
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\KoordinatorMediaKreatif\DashboardService::class)->getDashboardData();
        return view('koordinator-media-kreatif.dashboard', $data);
    }
}

