<?php

namespace App\Services\Sekretaris;

use App\Models\Notulensi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class NotulensiService
{
    public function index(): array
    {
        $notulensi = Notulensi::with(['creator', 'updater'])
            ->latest()
            ->paginate(10);
            
        return compact('notulensi');
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
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'isi_notulensi' => 'required|string',
            'tempat' => 'nullable|string|max:255',
            'peserta' => 'nullable|string',
            'kesimpulan' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Notulensi::create($validated);

        return redirect()->route('sekretaris.notulensi.index')
            ->with('success', 'Notulensi berhasil ditambahkan.');
    }

    public function show(Notulensi $notulensi): array
    {
        $notulensi->load(['creator', 'updater', 'absens.user']);
        return compact('notulensi');
    }

    public function edit(Notulensi $notulensi): array
    {
        return compact('notulensi');
    }

    public function update(Request $request, Notulensi $notulensi): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'isi_notulensi' => 'required|string',
            'tempat' => 'nullable|string|max:255',
            'peserta' => 'nullable|string',
            'kesimpulan' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $notulensi->update($validated);

        return redirect()->route('sekretaris.notulensi.index')
            ->with('success', 'Notulensi berhasil diperbarui.');
    }

    public function destroy(Notulensi $notulensi): RedirectResponse
    {
        $notulensi->delete();

        return redirect()->route('sekretaris.notulensi.index')
            ->with('success', 'Notulensi berhasil dihapus.');
    }
}

