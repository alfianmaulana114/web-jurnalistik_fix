<?php

namespace App\Services\AnggotaLitbang;

use App\Models\Brief;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class BriefService
{
    /**
     * Mengambil daftar brief untuk ditampilkan pada index.
     *
     * @return array
     */
    public function index(): array
    {
        $briefs = Brief::with(['contents'])->latest()->paginate(10);
        $totalBriefs = Brief::count();
        return compact('briefs', 'totalBriefs');
    }

    /**
     * Data tambahan untuk form pembuatan brief.
     *
     * @return array
     */
    public function create(): array
    {
        return [];
    }

    /**
     * Validasi dan simpan brief baru.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'isi_brief' => 'required|string',
            'link_referensi' => 'nullable|string',
        ]);

        Brief::create($validated);

        return redirect()->route('anggota-litbang.briefs.index')
            ->with('success', 'Brief berhasil ditambahkan.');
    }

    /**
     * Mengambil detail brief beserta relasi konten.
     *
     * @param Brief $brief
     * @return array
     */
    public function show(Brief $brief): array
    {
        $brief->load(['contents']);
        return compact('brief');
    }

    /**
     * Data untuk form edit brief.
     *
     * @param Brief $brief
     * @return array
     */
    public function edit(Brief $brief): array
    {
        return compact('brief');
    }

    /**
     * Validasi dan pembaruan brief.
     *
     * @param Request $request
     * @param Brief $brief
     * @return RedirectResponse
     */
    public function update(Request $request, Brief $brief): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'isi_brief' => 'required|string',
            'link_referensi' => 'nullable|string',
        ]);

        $brief->update($validated);

        return redirect()->route('anggota-litbang.briefs.index')
            ->with('success', 'Brief berhasil diperbarui.');
    }

    /**
     * Menghapus brief.
     *
     * @param Brief $brief
     * @return RedirectResponse
     */
    public function destroy(Brief $brief): RedirectResponse
    {
        $brief->delete();
        return redirect()->route('anggota-litbang.briefs.index')
            ->with('success', 'Brief berhasil dihapus.');
    }
}