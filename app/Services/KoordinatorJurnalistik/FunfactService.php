<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Funfact;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Service untuk manajemen Funfact
 * 
 * Menangani semua logika bisnis untuk CRUD funfact
 */
class FunfactService
{
    /**
     * Menampilkan daftar funfact dengan pagination
     * 
     * @return array
     */
    public function index(): array
    {
        $funfacts = Funfact::with(['creator'])
            ->latest()
            ->paginate(10);
            
        $totalFunfacts = Funfact::count();
            
        return compact('funfacts', 'totalFunfacts');
    }

    /**
     * Menampilkan form untuk membuat funfact baru
     * 
     * @return array
     */
    public function create(): array
    {
        return [];
    }

    /**
     * Menyimpan funfact baru ke database
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'link_referensi' => 'nullable|string',
        ]);

        // Tambahkan created_by dari user yang sedang login
        $validated['created_by'] = Auth::id();

        Funfact::create($validated);

        return redirect()->route('koordinator-jurnalistik.funfacts.index')
            ->with('success', 'Funfact berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail funfact
     * 
     * @param Funfact $funfact
     * @return array
     */
    public function show(Funfact $funfact): array
    {
        $funfact->load(['creator']);
        return compact('funfact');
    }

    /**
     * Menampilkan form untuk mengedit funfact
     * 
     * @param Funfact $funfact
     * @return array
     */
    public function edit(Funfact $funfact): array
    {
        return compact('funfact');
    }

    /**
     * Memperbarui funfact di database
     * 
     * @param Request $request
     * @param Funfact $funfact
     * @return RedirectResponse
     */
    public function update(Request $request, Funfact $funfact): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'link_referensi' => 'nullable|string',
        ]);

        $funfact->update($validated);

        return redirect()->route('koordinator-jurnalistik.funfacts.index')
            ->with('success', 'Funfact berhasil diperbarui.');
    }

    /**
     * Menghapus funfact dari database
     * 
     * @param Funfact $funfact
     * @return RedirectResponse
     */
    public function destroy(Funfact $funfact): RedirectResponse
    {
        $funfact->delete();

        return redirect()->route('koordinator-jurnalistik.funfacts.index')
            ->with('success', 'Funfact berhasil dihapus.');
    }
}

