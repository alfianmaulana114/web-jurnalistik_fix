<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Brief;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BriefService
{
    public function index(Request $request): array
    {
        $query = Brief::with(['contents']);

        // Filter by search (judul atau isi_brief)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('isi_brief', 'like', '%' . $search . '%');
            });
        }

        // Filter by tanggal
        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $briefs = $query->latest()->paginate(10)->withQueryString();
        $totalBriefs = Brief::count();
            
        return compact('briefs', 'totalBriefs');
    }

    public function create(): array
    {
        return [];
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'isi_brief' => 'required|string',
            'link_referensi' => 'nullable|string',
        ]);

        Brief::create($validated);

        return redirect()->route('koordinator-jurnalistik.briefs.index')
            ->with('success', 'Brief berhasil ditambahkan.');
    }

    public function show(Brief $brief): array
    {
        $brief->load(['contents']);
        return compact('brief');
    }

    public function edit(Brief $brief): array
    {
        return compact('brief');
    }

    public function update(Request $request, Brief $brief): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'isi_brief' => 'required|string',
            'link_referensi' => 'nullable|string',
        ]);

        $brief->update($validated);

        return redirect()->route('koordinator-jurnalistik.briefs.index')
            ->with('success', 'Brief berhasil diperbarui.');
    }

    public function destroy(Brief $brief): RedirectResponse
    {
        $brief->delete();

        return redirect()->route('koordinator-jurnalistik.briefs.index')
            ->with('success', 'Brief berhasil dihapus.');
    }
}


