<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProkerController extends Controller
{
    /**
     * Display a listing of prokers.
     */
    public function index(): View
    {
        $prokers = Proker::with(['creator', 'panitia'])
            ->latest()
            ->paginate(10);
            
        return view('koordinator-jurnalistik.prokers.index', compact('prokers'));
    }

    /**
     * Show the form for creating a new proker.
     */
    public function create(): View
    {
        $users = User::all();
        return view('koordinator-jurnalistik.prokers.create', compact('users'));
    }

    /**
     * Store a newly created proker in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_proker' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => ['required', Rule::in(array_keys(Proker::getAllStatuses()))],
            'catatan' => 'nullable|string',
            'panitia' => 'array',
            'panitia.*.user_id' => 'required|exists:users,id',
            'panitia.*.jabatan_panitia' => 'required|string|max:255',
            'panitia.*.tugas_khusus' => 'nullable|string',
        ]);

        // Assume current user is koordinator jurnalistik (ID 1 for now)
        $validated['created_by'] = 1;

        $proker = Proker::create($validated);

        // Attach panitia if provided
        if (isset($validated['panitia'])) {
            foreach ($validated['panitia'] as $panitiaData) {
                $proker->panitia()->attach($panitiaData['user_id'], [
                    'jabatan_panitia' => $panitiaData['jabatan_panitia'],
                    'tugas_khusus' => $panitiaData['tugas_khusus'] ?? null,
                ]);
            }
        }

        return redirect()->route('koordinator-jurnalistik.prokers.index')
            ->with('success', 'Proker berhasil ditambahkan.');
    }

    /**
     * Display the specified proker.
     */
    public function show(Proker $proker): View
    {
        $proker->load(['creator', 'panitia', 'designs']);
        return view('koordinator-jurnalistik.prokers.show', compact('proker'));
    }

    /**
     * Show the form for editing the specified proker.
     */
    public function edit(Proker $proker): View
    {
        $proker->load('panitia');
        $users = User::all();
        return view('koordinator-jurnalistik.prokers.edit', compact('proker', 'users'));
    }

    /**
     * Update the specified proker in storage.
     */
    public function update(Request $request, Proker $proker): RedirectResponse
    {
        $validated = $request->validate([
            'nama_proker' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => ['required', Rule::in(array_keys(Proker::getAllStatuses()))],
            'catatan' => 'nullable|string',
            'panitia' => 'array',
            'panitia.*.user_id' => 'required|exists:users,id',
            'panitia.*.jabatan_panitia' => 'required|string|max:255',
            'panitia.*.tugas_khusus' => 'nullable|string',
        ]);

        $proker->update($validated);

        // Sync panitia
        $proker->panitia()->detach();
        if (isset($validated['panitia'])) {
            foreach ($validated['panitia'] as $panitiaData) {
                $proker->panitia()->attach($panitiaData['user_id'], [
                    'jabatan_panitia' => $panitiaData['jabatan_panitia'],
                    'tugas_khusus' => $panitiaData['tugas_khusus'] ?? null,
                ]);
            }
        }

        return redirect()->route('koordinator-jurnalistik.prokers.index')
            ->with('success', 'Proker berhasil diperbarui.');
    }

    /**
     * Remove the specified proker from storage.
     */
    public function destroy(Proker $proker): RedirectResponse
    {
        $proker->panitia()->detach();
        $proker->delete();

        return redirect()->route('koordinator-jurnalistik.prokers.index')
            ->with('success', 'Proker berhasil dihapus.');
    }
}