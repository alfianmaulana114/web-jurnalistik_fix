<?php

namespace App\Services\KoordinatorLitbang;

use App\Models\Penjadwalan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class PenjadwalanService
{
    public function index(Request $request): array
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $bulan = max(1, min(12, (int) $bulan));
        $tahun = max(2020, min(2100, (int) $tahun));

        $anggotaLitbang = User::whereIn('role', [
            User::ROLE_ANGGOTA_LITBANG,
            User::ROLE_KOORDINATOR_LITBANG,
        ])->orderBy('name')->get();

        // Filter penjadwalan hanya untuk anggota litbang
        $litbangUserIds = $anggotaLitbang->pluck('id');
        
        $penjadwalan = Penjadwalan::with(['user', 'creator'])
            ->whereIn('user_id', $litbangUserIds)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->orderBy('user_id')
            ->get();

        $events = $penjadwalan->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->user->name,
                'start' => $item->tanggal->format('Y-m-d'),
                'user_id' => $item->user_id,
                'user_name' => $item->user->name,
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'status_label' => Penjadwalan::getAllStatus()[$item->status] ?? $item->status,
            ];
        });

        return [
            'penjadwalan' => $penjadwalan,
            'events' => $events,
            'anggotaLitbang' => $anggotaLitbang,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanLabel' => $this->getBulanLabel($bulan),
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePenjadwalan($request);

        $existing = Penjadwalan::where('user_id', $validated['user_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Anggota ini sudah memiliki jadwal pada tanggal tersebut.');
        }

        Penjadwalan::create([
            'user_id' => $validated['user_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => $validated['status'] ?? 'pending',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('koordinator-litbang.penjadwalan.index')->with('success', 'Penjadwalan berhasil ditambahkan');
    }

    public function edit(int $id): array
    {
        $penjadwalan = Penjadwalan::with(['user', 'creator'])->findOrFail($id);
        $anggotaLitbang = User::whereIn('role', [
            User::ROLE_ANGGOTA_LITBANG,
            User::ROLE_KOORDINATOR_LITBANG,
        ])->orderBy('name')->get();

        return [
            'penjadwalan' => $penjadwalan,
            'anggotaLitbang' => $anggotaLitbang,
        ];
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $this->validatePenjadwalan($request, $id);
        $penjadwalan = Penjadwalan::findOrFail($id);

        $existing = Penjadwalan::where('user_id', $validated['user_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('id', '!=', $id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Anggota ini sudah memiliki jadwal pada tanggal tersebut.');
        }

        $penjadwalan->update([
            'user_id' => $validated['user_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => $validated['status'] ?? 'pending',
        ]);

        return redirect()->route('koordinator-litbang.penjadwalan.index')->with('success', 'Penjadwalan berhasil diperbarui');
    }

    public function destroy(int $id): RedirectResponse
    {
        $penjadwalan = Penjadwalan::findOrFail($id);
        $penjadwalan->delete();
        return redirect()->route('koordinator-litbang.penjadwalan.index')->with('success', 'Penjadwalan berhasil dihapus');
    }

    private function validatePenjadwalan(Request $request, ?int $id = null): array
    {
        $rules = [
            'user_id' => ['required', 'exists:users,id'],
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
            'status' => 'nullable|in:pending,completed,cancelled',
        ];

        $validated = $request->validate($rules);

        $user = User::find($validated['user_id']);
        if (!$user || !in_array($user->role, [User::ROLE_ANGGOTA_LITBANG, User::ROLE_KOORDINATOR_LITBANG])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'user_id' => ['User yang dipilih harus anggota atau koordinator litbang.'],
            ]);
        }

        return $validated;
    }

    private function getBulanLabel(int $bulan): string
    {
        $bulanLabels = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $bulanLabels[$bulan] ?? 'Bulan tidak valid';
    }
}