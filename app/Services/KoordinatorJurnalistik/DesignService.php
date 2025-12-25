<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Design;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class DesignService
{
    public function index(Request $request): array
    {
        $query = Design::with(['berita']);

        // Filter by search (judul atau catatan)
        // Laravel's query builder automatically escapes LIKE parameters, but we validate input
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            // Limit search length to prevent DoS
            if (strlen($search) > 255) {
                $search = substr($search, 0, 255);
            }
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('catatan', 'like', '%' . $search . '%');
            });
        }

        // Filter by jenis - validate against allowed types
        if ($request->has('jenis') && $request->jenis) {
            $allowedJenis = ['image', 'video', 'infographic', 'other'];
            if (in_array($request->jenis, $allowedJenis)) {
                $query->where('jenis', $request->jenis);
            }
        }

        $designs = $query->latest()->paginate(10)->withQueryString();

        // Berita tersedia untuk pemilihan di modal index (mirip caption)
        // Kecualikan berita yang sudah memiliki desain terkait
        $usedNewsIdsForDesigns = Design::whereNotNull('berita_id')->pluck('berita_id')->unique()->toArray();
        $availableNews = News::with('category')
            ->whereNotIn('id', $usedNewsIdsForDesigns)
            ->latest()
            ->get();
        
        return compact('designs', 'availableNews');
    }

    public function create(Request $request = null): array
    {
        $availableNews = News::latest()->get();
        
        $selectedNews = null;
        $selectedNewsTitle = null;
        
        if ($request && $request->has('news_id')) {
            $selectedNews = News::find($request->news_id);
            $selectedNewsTitle = $request->has('news_title') 
                ? $request->news_title 
                : ($selectedNews ? ($selectedNews->title ?? $selectedNews->judul) : null);
        }

        return compact('availableNews', 'selectedNews', 'selectedNewsTitle');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'media_url' => 'required|url',
            'jenis' => ['required', Rule::in(array_keys(Design::getJenisOptions()))],
            'catatan' => 'nullable|string',
            'berita_id' => 'nullable|exists:news,id',
        ]);

        // Optional: track creator if auth available
        if (Auth::check()) {
            $validated['created_by'] = Auth::id();
        }

        // Derive title from selected news (no manual input)
        $news = isset($validated['berita_id']) ? News::find($validated['berita_id']) : null;
        $validated['judul'] = $news ? ($news->title ?? $news->judul ?? 'Tanpa Judul') : 'Tanpa Judul';

        Design::create($validated);

        return redirect()->route('koordinator-jurnalistik.designs.index')
            ->with('success', 'Desain berhasil ditambahkan.');
    }

    public function show(Design $design): array
    {
        $design->load(['berita']);
        return compact('design');
    }

    public function edit(Design $design): array
    {
        $availableNews = News::latest()->get();
        return compact('design', 'availableNews');
    }

    public function update(Request $request, Design $design): RedirectResponse
    {
        $validated = $request->validate([
            'media_url' => 'required|url',
            'jenis' => ['required', Rule::in(array_keys(Design::getJenisOptions()))],
            'catatan' => 'nullable|string',
            'berita_id' => 'nullable|exists:news,id',
        ]);
        // Always sync judul with selected news if provided; else keep existing
        $news = isset($validated['berita_id']) ? News::find($validated['berita_id']) : null;
        $validated['judul'] = $news ? ($news->title ?? $news->judul ?? $design->judul) : $design->judul;

        $design->update($validated);

        return redirect()->route('koordinator-jurnalistik.designs.index')
            ->with('success', 'Desain berhasil diperbarui.');
    }

    public function destroy(Design $design): RedirectResponse
    {
        $design->delete();

        return redirect()->route('koordinator-jurnalistik.designs.index')
            ->with('success', 'Desain berhasil dihapus.');
    }
}


