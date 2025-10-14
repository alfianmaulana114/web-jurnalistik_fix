<?php

namespace App\Http\Controllers;

use App\Models\Brief;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class BriefController extends Controller
{
    /**
     * Display a listing of briefs.
     */
    public function index(): View
    {
        $briefs = Brief::with(['contents'])
            ->latest()
            ->paginate(10);
            
        $totalBriefs = Brief::count();
            
        return view('koordinator-jurnalistik.briefs.index', compact(
            'briefs', 
            'totalBriefs'
        ));
    }

    /**
     * Show the form for creating a new brief.
     */
    public function create(): View
    {
        return view('koordinator-jurnalistik.briefs.create');
    }

    /**
     * Store a newly created brief in storage.
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

        return redirect()->route('koordinator-jurnalistik.briefs.index')
            ->with('success', 'Brief berhasil ditambahkan.');
    }

    /**
     * Display the specified brief.
     */
    public function show(Brief $brief): View
    {
        $brief->load(['contents']);
        return view('koordinator-jurnalistik.briefs.show', compact('brief'));
    }

    /**
     * Show the form for editing the specified brief.
     */
    public function edit(Brief $brief): View
    {
        return view('koordinator-jurnalistik.briefs.edit', compact('brief'));
    }

    /**
     * Update the specified brief in storage.
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

        return redirect()->route('koordinator-jurnalistik.briefs.index')
            ->with('success', 'Brief berhasil diperbarui.');
    }

    /**
     * Remove the specified brief from storage.
     */
    public function destroy(Brief $brief): RedirectResponse
    {
        $brief->delete();

        return redirect()->route('koordinator-jurnalistik.briefs.index')
            ->with('success', 'Brief berhasil dihapus.');
    }
}