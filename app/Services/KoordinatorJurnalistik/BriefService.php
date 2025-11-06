<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Brief;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BriefService
{
    public function index(): array
    {
        $briefs = Brief::with(['contents'])
            ->latest()
            ->paginate(10);
            
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


