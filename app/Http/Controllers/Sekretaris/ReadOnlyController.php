<?php

namespace App\Http\Controllers\Sekretaris;

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

class ReadOnlyController extends Controller
{
    public function newsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->index($request);
        $data['layout'] = 'layouts.sekretaris';
        $data['routePrefix'] = 'sekretaris.view';
        return view('sekretaris.read-only.news.index', $data);
    }

    public function newsShow($id): View
    {
        $news = News::findOrFail($id);
        return view('sekretaris.read-only.news.show', [
            'news' => $news,
            'layout' => 'layouts.sekretaris',
            'routePrefix' => 'sekretaris.view',
        ]);
    }

    public function prokersIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\ProkerService::class)->index($request);
        $data['layout'] = 'layouts.sekretaris';
        $data['routePrefix'] = 'sekretaris.view';
        return view('sekretaris.read-only.prokers.index', $data);
    }

    public function prokersShow(Proker $proker): View
    {
        $proker->load(['creator', 'panitias']);
        return view('sekretaris.read-only.prokers.show', [
            'proker' => $proker,
            'layout' => 'layouts.sekretaris',
            'routePrefix' => 'sekretaris.view',
        ]);
    }

    public function briefsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefService::class)->index($request);
        $data['layout'] = 'layouts.sekretaris';
        $data['routePrefix'] = 'sekretaris.view';
        return view('sekretaris.read-only.briefs.index', $data);
    }

    public function briefsShow(Brief $brief): View
    {
        $brief->load(['contents']);
        return view('sekretaris.read-only.briefs.show', [
            'brief' => $brief,
            'layout' => 'layouts.sekretaris',
            'routePrefix' => 'sekretaris.view',
        ]);
    }

    public function contentsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\ContentService::class)->index($request);
        $data['layout'] = 'layouts.sekretaris';
        $data['routePrefix'] = 'sekretaris.view';
        return view('sekretaris.read-only.contents.index', $data);
    }

    public function contentsShow(Content $content): View
    {
        $content->load(['brief', 'creator', 'berita', 'desain']);
        return view('sekretaris.read-only.contents.show', [
            'content' => $content,
            'layout' => 'layouts.sekretaris',
            'routePrefix' => 'sekretaris.view',
        ]);
    }

    public function designsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\DesignService::class)->index($request);
        $data['layout'] = 'layouts.sekretaris';
        $data['routePrefix'] = 'sekretaris.view';
        return view('sekretaris.read-only.designs.index', $data);
    }

    public function designsShow(Design $design): View
    {
        return view('sekretaris.read-only.designs.show', [
            'design' => $design,
            'layout' => 'layouts.sekretaris',
            'routePrefix' => 'sekretaris.view',
        ]);
    }

    public function funfactsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\FunfactService::class)->index($request);
        $data['layout'] = 'layouts.sekretaris';
        $data['routePrefix'] = 'sekretaris.view';
        return view('sekretaris.read-only.funfacts.index', $data);
    }

    public function funfactsShow(Funfact $funfact): View
    {
        return view('sekretaris.read-only.funfacts.show', [
            'funfact' => $funfact,
            'layout' => 'layouts.sekretaris',
            'routePrefix' => 'sekretaris.view',
        ]);
    }

    public function usersIndex(Request $request): View
    {
        $query = User::query();
        // Filter by search - validate and limit input length
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
            'layout' => 'layouts.sekretaris',
            'routePrefix' => 'sekretaris.view',
        ]);
    }

    public function usersShow(User $user): View
    {
        return view('sekretaris.read-only.users.show', [
            'user' => $user,
            'layout' => 'layouts.sekretaris',
            'routePrefix' => 'sekretaris.view',
        ]);
    }

    public function briefHumasIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefHumasService::class)->index($request);
        $data['layout'] = 'layouts.sekretaris';
        $data['routePrefix'] = 'sekretaris.view';
        return view('sekretaris.read-only.brief-humas.index', $data);
    }

    public function briefHumasShow(BriefHumas $briefHumas): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\BriefHumasService::class)->show($briefHumas);
        $data['layout'] = 'layouts.sekretaris';
        $data['routePrefix'] = 'sekretaris.view';
        return view('sekretaris.read-only.brief-humas.show', $data);
    }
}
