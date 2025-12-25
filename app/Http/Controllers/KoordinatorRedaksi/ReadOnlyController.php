<?php

namespace App\Http\Controllers\KoordinatorRedaksi;

use App\Http\Controllers\Controller;
use App\Models\Proker;
use App\Models\Brief;
use App\Models\Content;
use App\Models\Design;
use App\Models\User;
use App\Models\BriefHumas;
use App\Models\Notulensi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadOnlyController extends Controller
{
    public function prokersIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\ProkerService::class)->index($request);
        $data['layout'] = 'layouts.koordinator-redaksi';
        $data['routePrefix'] = 'koordinator-redaksi.view';
        return view('sekretaris.read-only.prokers.index', $data);
    }

    public function prokersShow(Proker $proker): View
    {
        $proker->load(['creator', 'panitias']);
        return view('sekretaris.read-only.prokers.show', [
            'proker' => $proker,
            'layout' => 'layouts.koordinator-redaksi',
            'routePrefix' => 'koordinator-redaksi.view',
        ]);
    }

    public function briefsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefService::class)->index($request);
        $data['layout'] = 'layouts.koordinator-redaksi';
        $data['routePrefix'] = 'koordinator-redaksi.view';
        return view('sekretaris.read-only.briefs.index', $data);
    }

    public function briefsShow(Brief $brief): View
    {
        $brief->load(['contents']);
        return view('sekretaris.read-only.briefs.show', [
            'brief' => $brief,
            'layout' => 'layouts.koordinator-redaksi',
            'routePrefix' => 'koordinator-redaksi.view',
        ]);
    }

    public function contentsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\ContentService::class)->index($request);
        $data['layout'] = 'layouts.koordinator-redaksi';
        $data['routePrefix'] = 'koordinator-redaksi.view';
        return view('sekretaris.read-only.contents.index', $data);
    }

    public function contentsShow(Content $content): View
    {
        $content->load(['brief', 'creator', 'berita', 'desain']);
        return view('sekretaris.read-only.contents.show', [
            'content' => $content,
            'layout' => 'layouts.koordinator-redaksi',
            'routePrefix' => 'koordinator-redaksi.view',
        ]);
    }

    public function designsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\DesignService::class)->index($request);
        $data['layout'] = 'layouts.koordinator-redaksi';
        $data['routePrefix'] = 'koordinator-redaksi.view';
        return view('sekretaris.read-only.designs.index', $data);
    }

    public function designsShow(Design $design): View
    {
        return view('sekretaris.read-only.designs.show', [
            'design' => $design,
            'layout' => 'layouts.koordinator-redaksi',
            'routePrefix' => 'koordinator-redaksi.view',
        ]);
    }

    public function usersIndex(Request $request): View
    {
        $query = User::query();
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            if (strlen($search) > 255) {
                $search = substr($search, 0, 255);
            }
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }
        if ($request->has('role') && $request->role) {
            $allowedRoles = array_keys(User::getAllRoles());
            if (in_array($request->role, $allowedRoles)) {
                $query->where('role', $request->role);
            }
        }
        if ($request->has('divisi') && $request->divisi) {
            $divisi = $request->divisi;
            $query->where(function($q) use ($divisi) {
                if ($divisi === 'redaksi') {
                    $q->whereIn('role', ['koordinator_redaksi', 'anggota_redaksi']);
                } elseif ($divisi === 'litbang') {
                    $q->whereIn('role', ['koordinator_litbang', 'anggota_litbang']);
                } elseif ($divisi === 'humas') {
                    $q->whereIn('role', ['koordinator_humas', 'anggota_humas']);
                } elseif ($divisi === 'media_kreatif') {
                    $q->whereIn('role', ['koordinator_media_kreatif', 'anggota_media_kreatif']);
                } elseif ($divisi === 'pengurus') {
                    $q->whereIn('role', ['sekretaris', 'bendahara']);
                }
            });
        }
        $users = $query->latest()->paginate(10)->withQueryString();
        return view('sekretaris.read-only.users.index', [
            'users' => $users,
            'layout' => 'layouts.koordinator-redaksi',
            'routePrefix' => 'koordinator-redaksi.view',
        ]);
    }

    public function usersShow(User $user): View
    {
        return view('sekretaris.read-only.users.show', [
            'user' => $user,
            'layout' => 'layouts.koordinator-redaksi',
            'routePrefix' => 'koordinator-redaksi.view',
        ]);
    }

    public function briefHumasIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefHumasService::class)->index($request);
        $data['layout'] = 'layouts.koordinator-redaksi';
        $data['routePrefix'] = 'koordinator-redaksi.view';
        return view('sekretaris.read-only.brief-humas.index', $data);
    }

    public function briefHumasShow(BriefHumas $briefHumas): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefHumasService::class)->show($briefHumas);
        $data['layout'] = 'layouts.koordinator-redaksi';
        $data['routePrefix'] = 'koordinator-redaksi.view';
        return view('sekretaris.read-only.brief-humas.show', $data);
    }

    public function kasAnggotaRiwayat(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\KasAnggotaService::class)->getRiwayat($request->all());
        return view('koordinator-redaksi.read-only.kas-anggota.riwayat', $data);
    }

    public function laporanKeuangan(Request $request): View
    {
        $filters = [
            'periode' => $request->get('periode', 'bulan_ini'),
            'bulan' => (int) $request->get('bulan', now()->month),
            'tahun' => (int) $request->get('tahun', now()->year),
            'tanggal_mulai' => $request->get('tanggal_mulai'),
            'tanggal_selesai' => $request->get('tanggal_selesai'),
        ];
        $data = app(\App\Services\KoordinatorJurnalistik\LaporanService::class)->getLaporanKeuanganData($filters);
        return view('koordinator-redaksi.read-only.laporan.index', $data);
    }

    public function sekretarisNotulensiIndex(): View
    {
        $data = app(\App\Services\Sekretaris\NotulensiService::class)->index();
        return view('koordinator-redaksi.read-only.notulensi.index', $data);
    }

    public function sekretarisNotulensiShow(Notulensi $notulensi): View
    {
        $data = app(\App\Services\Sekretaris\NotulensiService::class)->show($notulensi);
        return view('koordinator-redaksi.read-only.notulensi.show', $data);
    }

    public function sekretarisNotulensiDownload(Notulensi $notulensi)
    {
        if (empty($notulensi->pdf_path)) {
            return back()->with('error', 'PDF tidak tersedia untuk notulensi ini.');
        }
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($notulensi->pdf_path)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
        $filename = 'Notulensi-' . \Illuminate\Support\Str::slug($notulensi->judul) . '.pdf';
        $fullPath = storage_path('app/public/' . $notulensi->pdf_path);
        return response()->download($fullPath, $filename);
    }

    public function sekretarisAbsenIndex(Request $request): View
    {
        $data = app(\App\Services\Sekretaris\AbsenService::class)->index($request->all());
        return view('koordinator-redaksi.read-only.sekretaris.absen.index', $data);
    }
}
