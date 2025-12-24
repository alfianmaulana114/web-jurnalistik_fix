<?php

namespace App\Services\KoordinatorMediaKreatif;

use App\Models\Penjadwalan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

/**
 * Service untuk Manajemen Penjadwalan Koordinator Media Kreatif
 * 
 * Service ini menangani semua logika bisnis terkait penjadwalan anggota media kreatif.
 * Mengikuti prinsip Single Responsibility dengan fokus pada operasi CRUD penjadwalan.
 */
class PenjadwalanService
{
    /**
     * Menampilkan semua penjadwalan dengan format untuk kalender
     * 
     * @param Request $request Request yang berisi filter bulan dan tahun
     * @return array Data penjadwalan dan anggota media kreatif
     */
    public function index(Request $request): array
    {
        // Ambil bulan dan tahun dari request, default bulan dan tahun sekarang
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        // Validasi bulan dan tahun
        $bulan = max(1, min(12, (int) $bulan));
        $tahun = max(2020, min(2100, (int) $tahun));

        // Ambil semua anggota media kreatif
        $anggotaMediaKreatif = User::whereIn('role', [
            User::ROLE_ANGGOTA_MEDIA_KREATIF,
            User::ROLE_KOORDINATOR_MEDIA_KREATIF
        ])->orderBy('name')->get();

        // Ambil penjadwalan untuk bulan dan tahun yang dipilih
        $mediaKreatifUserIds = $anggotaMediaKreatif->pluck('id');
        
        $penjadwalan = Penjadwalan::with(['user'])
            ->whereIn('user_id', $mediaKreatifUserIds)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->orderBy('user_id')
            ->get();

        // Format data untuk kalender
        $events = $penjadwalan->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->user->name,
                'start' => $item->tanggal->format('Y-m-d'),
                'extendedProps' => [
                    'user_id' => $item->user_id,
                    'keterangan' => $item->keterangan,
                    'status' => $item->status,
                ],
            ];
        });

        return [
            'penjadwalan' => $penjadwalan,
            'events' => $events,
            'anggotaMediaKreatif' => $anggotaMediaKreatif,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanLabel' => $this->getBulanLabel($bulan),
        ];
    }

    /**
     * Menyimpan penjadwalan baru
     * 
     * @param Request $request Request dari form
     * @return RedirectResponse Redirect ke halaman index dengan pesan sukses
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePenjadwalan($request);

        // Cek apakah sudah ada jadwal untuk user dan tanggal yang sama
        $existing = Penjadwalan::where('user_id', $validated['user_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anggota ini sudah memiliki jadwal pada tanggal tersebut.');
        }

        // Buat penjadwalan baru
        Penjadwalan::create([
            'user_id' => $validated['user_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => $validated['status'] ?? 'pending',
            'created_by' => auth()->id(),
        ]);

        $bulan = Carbon::parse($validated['tanggal'])->month;
        $tahun = Carbon::parse($validated['tanggal'])->year;

        return redirect()->route('koordinator-media-kreatif.penjadwalan.index', [
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->with('success', 'Penjadwalan berhasil ditambahkan');
    }

    /**
     * Mengambil data untuk form edit
     * 
     * @param int $id ID penjadwalan
     * @return array Data penjadwalan dan anggota media kreatif
     */
    public function edit(int $id): array
    {
        $penjadwalan = Penjadwalan::with(['user'])->findOrFail($id);
        
        $anggotaMediaKreatif = User::whereIn('role', [
            User::ROLE_ANGGOTA_MEDIA_KREATIF,
            User::ROLE_KOORDINATOR_MEDIA_KREATIF
        ])->orderBy('name')->get();

        return [
            'penjadwalan' => $penjadwalan,
            'anggotaMediaKreatif' => $anggotaMediaKreatif,
        ];
    }

    /**
     * Memperbarui penjadwalan yang sudah ada
     * 
     * @param Request $request Request dari form
     * @param int $id ID penjadwalan
     * @return RedirectResponse Redirect ke halaman index dengan pesan sukses
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $this->validatePenjadwalan($request, $id);
        $penjadwalan = Penjadwalan::findOrFail($id);

        // Cek apakah sudah ada jadwal lain untuk user dan tanggal yang sama
        $existing = Penjadwalan::where('user_id', $validated['user_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('id', '!=', $id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anggota ini sudah memiliki jadwal pada tanggal tersebut.');
        }

        // Update penjadwalan
        $penjadwalan->update([
            'user_id' => $validated['user_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => $validated['status'] ?? 'pending',
        ]);

        $bulan = Carbon::parse($validated['tanggal'])->month;
        $tahun = Carbon::parse($validated['tanggal'])->year;

        return redirect()->route('koordinator-media-kreatif.penjadwalan.index', [
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->with('success', 'Penjadwalan berhasil diperbarui');
    }

    /**
     * Menghapus penjadwalan
     * 
     * @param int $id ID penjadwalan
     * @return RedirectResponse Redirect ke halaman index dengan pesan sukses
     */
    public function destroy(int $id): RedirectResponse
    {
        $penjadwalan = Penjadwalan::findOrFail($id);
        $bulan = $penjadwalan->tanggal->month;
        $tahun = $penjadwalan->tanggal->year;
        
        $penjadwalan->delete();

        return redirect()->route('koordinator-media-kreatif.penjadwalan.index', [
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->with('success', 'Penjadwalan berhasil dihapus');
    }

    /**
     * Validasi data penjadwalan dari request
     * 
     * @param Request $request Request dari form
     * @param int|null $id ID penjadwalan (untuk update, null untuk create)
     * @return array Data yang sudah divalidasi
     */
    private function validatePenjadwalan(Request $request, ?int $id = null): array
    {
        $rules = [
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
            'status' => 'nullable|in:pending,completed,cancelled',
        ];

        $validated = $request->validate($rules);
        
        // Validasi tambahan untuk memastikan user adalah anggota media kreatif
        $user = User::find($validated['user_id']);
        if (!$user || !in_array($user->role, [User::ROLE_ANGGOTA_MEDIA_KREATIF, User::ROLE_KOORDINATOR_MEDIA_KREATIF])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'user_id' => ['User yang dipilih harus anggota atau koordinator media kreatif.'],
            ]);
        }

        return $validated;
    }

    /**
     * Mendapatkan label bulan dalam bahasa Indonesia
     * 
     * @param int $bulan Nomor bulan (1-12)
     * @return string Label bulan
     */
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

