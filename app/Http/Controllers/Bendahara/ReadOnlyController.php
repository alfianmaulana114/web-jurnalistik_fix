<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Proker;
use App\Models\Brief;
use App\Models\Content;
use App\Models\Design;
use App\Models\User;
use App\Models\Funfact;
use App\Models\BriefHumas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Notulensi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller untuk Bendahara - Read Only Access
 * 
 * Controller ini memberikan akses read-only untuk bendahara ke semua fitur
 * koordinator-jurnalistik. Bendahara hanya bisa melihat data tanpa bisa
 * melakukan CRUD operations.
 */
class ReadOnlyController extends Controller
{
    /**
     * Menampilkan dashboard koordinator jurnalistik (read-only)
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\DashboardService::class)->getDashboardData();
        return view('bendahara.read-only.dashboard', $data);
    }

    /**
     * Menampilkan daftar berita (read-only)
     */
    public function newsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->index($request);
        return view('bendahara.read-only.news.index', $data);
    }

    /**
     * Menampilkan detail berita (read-only)
     */
    public function newsShow($id): View
    {
        $news = News::findOrFail($id);
        return view('bendahara.read-only.news.show', compact('news'));
    }

    /**
     * Menampilkan daftar proker (read-only)
     */
    public function prokersIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\ProkerService::class)->index($request);
        return view('bendahara.read-only.prokers.index', $data);
    }

    /**
     * Menampilkan detail proker (read-only)
     */
    public function prokersShow(Proker $proker): View
    {
        $proker->load(['creator', 'panitias']);
        return view('bendahara.read-only.prokers.show', compact('proker'));
    }

    /**
     * Menampilkan daftar brief (read-only)
     */
    public function briefsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefService::class)->index($request);
        return view('bendahara.read-only.briefs.index', $data);
    }

    /**
     * Menampilkan detail brief (read-only)
     */
    public function briefsShow(Brief $brief): View
    {
        $brief->load(['contents']);
        return view('bendahara.read-only.briefs.show', compact('brief'));
    }

    /**
     * Menampilkan daftar content (read-only)
     */
    public function contentsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\ContentService::class)->index($request);
        return view('bendahara.read-only.contents.index', $data);
    }

    /**
     * Menampilkan detail content (read-only)
     */
    public function contentsShow(Content $content): View
    {
        $content->load(['brief', 'creator', 'berita', 'desain']);
        return view('bendahara.read-only.contents.show', compact('content'));
    }

    /**
     * Menampilkan daftar design (read-only)
     */
    public function designsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\DesignService::class)->index($request);
        return view('bendahara.read-only.designs.index', $data);
    }

    /**
     * Menampilkan detail design (read-only)
     */
    public function designsShow(Design $design): View
    {
        return view('bendahara.read-only.designs.show', compact('design'));
    }

    /**
     * Menampilkan daftar funfact (read-only)
     */
    public function funfactsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\FunfactService::class)->index($request);
        return view('bendahara.read-only.funfacts.index', $data);
    }

    /**
     * Menampilkan detail funfact (read-only)
     */
    public function funfactsShow(Funfact $funfact): View
    {
        return view('bendahara.read-only.funfacts.show', compact('funfact'));
    }

    /**
     * Menampilkan daftar user (read-only)
     */
    public function usersIndex(Request $request): View
    {
        $query = User::query();

        // Filter by search (name, email, atau NIM)
        // Laravel's query builder automatically escapes LIKE parameters, but we validate input
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            // Limit search length to prevent DoS
            if (strlen($search) > 255) {
                $search = substr($search, 0, 255);
            }
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        // Filter by role - validate against allowed roles
        if ($request->has('role') && $request->role) {
            $allowedRoles = array_keys(User::getAllRoles());
            if (in_array($request->role, $allowedRoles)) {
                $query->where('role', $request->role);
            }
        }

        // Filter by divisi (berdasarkan role)
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
        return view('bendahara.read-only.users.index', compact('users'));
    }

    /**
     * Menampilkan detail user (read-only)
     */
    public function usersShow(User $user): View
    {
        return view('bendahara.read-only.users.show', compact('user'));
    }

    public function briefHumasIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefHumasService::class)->index($request);
        return view('bendahara.read-only.brief-humas.index', $data);
    }

    public function briefHumasShow(BriefHumas $briefHumas): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefHumasService::class)->show($briefHumas);
        return view('bendahara.read-only.brief-humas.show', $data);
    }

    // Sekretaris (Read-Only)
    public function sekretarisNotulensiIndex(): View
    {
        $data = app(\App\Services\Sekretaris\NotulensiService::class)->index();
        return view('bendahara.read-only.sekretaris.notulensi.index', $data);
    }

    public function sekretarisNotulensiShow(Notulensi $notulensi): View
    {
        $data = app(\App\Services\Sekretaris\NotulensiService::class)->show($notulensi);
        return view('bendahara.read-only.sekretaris.notulensi.show', $data);
    }

    public function sekretarisAbsenIndex(Request $request): View
    {
        $data = app(\App\Services\Sekretaris\AbsenService::class)->index($request->all());
        return view('bendahara.read-only.sekretaris.absen.index', $data);
    }

    public function sekretarisNotulensiDownload(Notulensi $notulensi)
    {
        if (empty($notulensi->pdf_path)) {
            return back()->with('error', 'PDF tidak tersedia untuk notulensi ini.');
        }
        if (!Storage::disk('public')->exists($notulensi->pdf_path)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
        $filename = 'Notulensi-' . Str::slug($notulensi->judul) . '.pdf';
        $fullPath = storage_path('app/public/' . $notulensi->pdf_path);
        return response()->download($fullPath, $filename);
    }
}

