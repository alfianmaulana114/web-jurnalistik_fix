<?php

namespace App\Services\Sekretaris;

use App\Models\Absen;
use App\Models\User;
use App\Models\Notulensi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsenService
{
    public function index(array $filters = []): array
    {
        $search = $filters['search'] ?? '';
        $notulensi_id = $filters['notulensi_id'] ?? null;
        $meeting_search = $filters['meeting_search'] ?? '';
        $bulan = $filters['bulan'] ?? null;

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        // Tampilkan semua anggota; peserta rapat akan otomatis ditandai hadir melalui sinkronisasi Notulensi

        $users = $query->orderBy('name')->get();

        // Absen berbasis rapat; jika rapat belum dipilih, tetap tampilkan daftar anggota
        if ($notulensi_id) {
            $absenData = Absen::where('notulensi_id', $notulensi_id)
                ->get()
                ->keyBy('user_id');
        } else {
            $absenData = collect();
        }
        $meetingsQuery = Notulensi::orderBy('tanggal', 'desc');
        if (!empty($meeting_search)) {
            $meetingsQuery->where(function ($q) use ($meeting_search) {
                $q->where('judul', 'like', '%' . $meeting_search . '%')
                  ->orWhere('tempat', 'like', '%' . $meeting_search . '%');
            });
        }
        if (!empty($bulan)) {
            $meetingsQuery->whereMonth('tanggal', (int) $bulan);
        }
        $meetings = $meetingsQuery->get();
        return compact('users', 'absenData', 'search', 'meetings', 'notulensi_id', 'meeting_search', 'bulan');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,tidak_hadir',
            'keterangan' => 'nullable|string',
            'notulensi_id' => 'required|exists:notulensi,id',
        ]);

        $tanggal = Carbon::parse($validated['tanggal']);
        $validated['bulan'] = $this->getBulanName($tanggal->month);
        $validated['tahun'] = $tanggal->year;
        $validated['created_by'] = auth()->id();

        Absen::create($validated);

        return redirect()->back()
            ->with('success', 'Absen berhasil ditambahkan.');
    }

    public function update(Request $request, Absen $absen): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:hadir,izin,sakit,tidak_hadir',
            'keterangan' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $absen->update($validated);

        return redirect()->back()
            ->with('success', 'Absen berhasil diperbarui.');
    }

    public function destroy(Absen $absen): RedirectResponse
    {
        $absen->delete();

        return redirect()->back()
            ->with('success', 'Absen berhasil dihapus.');
    }

    public function storeBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'absens' => 'required|array',
            'absens.*.user_id' => 'required|exists:users,id',
            'absens.*.status' => 'nullable|in:hadir,izin,sakit,tidak_hadir',
            'absens.*.keterangan' => 'nullable|string',
            'notulensi_id' => 'required|exists:notulensi,id',
        ]);

        $tanggal = Carbon::parse($validated['tanggal']);
        $bulan = $this->getBulanName($tanggal->month);
        $tahun = $tanggal->year;

        DB::transaction(function () use ($validated, $bulan, $tahun) {
            foreach ($validated['absens'] as $absenData) {
                // Hanya simpan jika status diisi
                if (!empty($absenData['status'])) {
                    Absen::updateOrCreate(
                        [
                            'user_id' => $absenData['user_id'],
                            'tanggal' => $validated['tanggal'],
                        ],
                        [
                            'status' => $absenData['status'],
                            'keterangan' => $absenData['keterangan'] ?? null,
                            'notulensi_id' => $validated['notulensi_id'] ?? null,
                            'bulan' => $bulan,
                            'tahun' => $tahun,
                            'created_by' => auth()->id(),
                        ]
                    );
                }
            }
        });

        return redirect()->back()
            ->with('success', 'Absen berhasil disimpan.');
    }

    private function getBulanName(int $month): string
    {
        return match($month) {
            1 => Absen::BULAN_JANUARI,
            2 => Absen::BULAN_FEBRUARI,
            3 => Absen::BULAN_MARET,
            4 => Absen::BULAN_APRIL,
            5 => Absen::BULAN_MEI,
            6 => Absen::BULAN_JUNI,
            7 => Absen::BULAN_JULI,
            8 => Absen::BULAN_AGUSTUS,
            9 => Absen::BULAN_SEPTEMBER,
            10 => Absen::BULAN_OKTOBER,
            11 => Absen::BULAN_NOVEMBER,
            12 => Absen::BULAN_DESEMBER,
            default => Absen::BULAN_JANUARI,
        };
    }
}

