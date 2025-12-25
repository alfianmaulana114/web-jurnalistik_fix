<?php

namespace App\Services\KoordinatorHumas;

use App\Models\BriefHumas;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class BriefHumasService
{
    public function index(Request $request): array
    {
        $q = trim((string) $request->get('q'));
        $briefs = BriefHumas::query()
            ->when($q, fn($qBuilder) => $qBuilder->where('judul', 'like', "%$q%"))
            ->latest()
            ->paginate(10);
        return compact('briefs', 'q');
    }

    public function create(): array
    {
        return [];
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'link_drive' => 'required|string|max:2048',
            'catatan' => 'nullable|string',
        ]);
        BriefHumas::create($validated);
        return redirect()->route('koordinator-humas.brief-humas.index')
            ->with('success', 'Brief Humas berhasil ditambahkan.');
    }

    public function show(BriefHumas $briefHumas): array
    {
        return compact('briefHumas');
    }

    public function edit(BriefHumas $briefHumas): array
    {
        return compact('briefHumas');
    }

    public function update(Request $request, BriefHumas $briefHumas): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'link_drive' => 'required|string|max:2048',
            'catatan' => 'nullable|string',
        ]);
        $briefHumas->update($validated);
        return redirect()->route('koordinator-humas.brief-humas.index')
            ->with('success', 'Brief Humas berhasil diperbarui.');
    }

    public function destroy(BriefHumas $briefHumas): RedirectResponse
    {
        $briefHumas->delete();
        return redirect()->route('koordinator-humas.brief-humas.index')
            ->with('success', 'Brief Humas berhasil dihapus.');
    }
}

