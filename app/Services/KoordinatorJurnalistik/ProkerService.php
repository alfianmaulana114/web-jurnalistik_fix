<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Proker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class ProkerService
{
    public function index(): array
    {
        $prokers = Proker::latest()->paginate(10);
        return compact('prokers');
    }

    public function create(): array
    {
        $users = User::all();
        return compact('users');
    }

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
        $validated['created_by'] = auth()->id();

        $proker = Proker::create($validated);

        // Sync panitia
        if (isset($validated['panitia'])) {
            foreach ($validated['panitia'] as $panitiaData) {
                $proker->panitia()->attach($panitiaData['user_id'], [
                    'jabatan_panitia' => $panitiaData['jabatan_panitia'],
                    'tugas_khusus' => $panitiaData['tugas_khusus'] ?? null,
                ]);
            }
        }

        // Get return route based on user role
        $route = auth()->user()->isSekretaris() ? 'sekretaris.proker.index' : 'koordinator-jurnalistik.prokers.index';

        return redirect()->route($route)
            ->with('success', 'Proker berhasil ditambahkan.');
    }

    public function show($id): array
    {
        $proker = Proker::with(['creator', 'panitia', 'designs'])->findOrFail($id);
        return compact('proker');
    }

    public function edit($id): array
    {
        $proker = Proker::with('panitia')->findOrFail($id);
        $users = User::all();
        return compact('proker', 'users');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $proker = Proker::findOrFail($id);
        
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

        // Get return route based on user role
        $route = auth()->user()->isSekretaris() ? 'sekretaris.proker.index' : 'koordinator-jurnalistik.prokers.index';

        return redirect()->route($route)
            ->with('success', 'Proker berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $proker = Proker::findOrFail($id);
        $proker->panitia()->detach();
        $proker->delete();

        // Get return route based on user role
        $route = auth()->user()->isSekretaris() ? 'sekretaris.proker.index' : 'koordinator-jurnalistik.prokers.index';

        return redirect()->route($route)
            ->with('success', 'Proker berhasil dihapus.');
    }
}


