<?php

namespace App\Services\Sekretaris;

use App\Models\Notulensi;
use App\Models\Absen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $users = User::orderBy('name')->get();
        return compact('users');
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
            'kesimpulan' => 'nullable|string',
            'peserta_user_ids' => 'nullable|array',
            'peserta_user_ids.*' => 'exists:users,id',
            'peserta_kehadiran' => 'nullable|array',
            'peserta_kehadiran.*' => 'in:hadir,izin,sakit,tidak_hadir',
            'pdf' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $validated['created_by'] = auth()->id();

        // Build peserta string from selected user IDs
        $userIds = $request->input('peserta_user_ids', []);
        if (!empty($userIds)) {
            $names = User::whereIn('id', $userIds)->pluck('name')->toArray();
            $validated['peserta'] = implode("\n", $names);
        }

        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('notulensi-pdfs', 'public');
            $validated['pdf_path'] = $path;
        }

        $notulensi = Notulensi::create($validated);

        // Sinkronkan status kehadiran per peserta ke Absen
        $attendanceMap = $request->input('peserta_kehadiran', []);
        $this->syncAttendanceFromNotulensi($notulensi, $attendanceMap, $userIds);

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
        $users = User::orderBy('name')->get();
        return compact('notulensi', 'users');
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
            'kesimpulan' => 'nullable|string',
            'peserta_user_ids' => 'nullable|array',
            'peserta_user_ids.*' => 'exists:users,id',
            'peserta_kehadiran' => 'nullable|array',
            'peserta_kehadiran.*' => 'in:hadir,izin,sakit,tidak_hadir',
            'pdf' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $validated['updated_by'] = auth()->id();

        // Build peserta string from selected user IDs
        $userIds = $request->input('peserta_user_ids', []);
        if (!empty($userIds)) {
            $names = User::whereIn('id', $userIds)->pluck('name')->toArray();
            $validated['peserta'] = implode("\n", $names);
        }

        if ($request->hasFile('pdf')) {
            if (!empty($notulensi->pdf_path)) {
                Storage::disk('public')->delete($notulensi->pdf_path);
            }
            $path = $request->file('pdf')->store('notulensi-pdfs', 'public');
            $validated['pdf_path'] = $path;
        }

        $notulensi->update($validated);

        // Sinkronkan status kehadiran per peserta ke Absen tanpa menimpa catatan manual yang sudah ada
        $attendanceMap = $request->input('peserta_kehadiran', []);
        $this->syncAttendanceFromNotulensi($notulensi, $attendanceMap, $userIds);

        return redirect()->route('sekretaris.notulensi.index')
            ->with('success', 'Notulensi berhasil diperbarui.');
    }

    public function destroy(Notulensi $notulensi): RedirectResponse
    {
        if (!empty($notulensi->pdf_path)) {
            Storage::disk('public')->delete($notulensi->pdf_path);
        }
        $notulensi->delete();

        return redirect()->route('sekretaris.notulensi.index')
            ->with('success', 'Notulensi berhasil dihapus.');
    }

    /**
     * Sinkronisasi absensi: peserta notulensi dianggap hadir.
     * - Pada create: buat record hadir untuk semua peserta.
     * - Pada update: hanya buat untuk peserta yang belum punya absensi pada tanggal rapat,
     *   tidak menimpa status yang sudah diubah manual.
     */
    private function syncAttendanceFromNotulensi(Notulensi $notulensi, array $attendanceMap, array $userIds): void
    {
        // Siapkan default status untuk peserta yang diinput (hadir jika tidak diset)
        foreach ($userIds as $userId) {
            if (!isset($attendanceMap[$userId]) || empty($attendanceMap[$userId])) {
                $attendanceMap[$userId] = Absen::STATUS_HADIR;
            }
        }

        $tanggal = Carbon::parse($notulensi->tanggal);
        $bulan = $this->getBulanName((int) $tanggal->month);
        $tahun = (int) $tanggal->year;
        $createdBy = auth()->id();

        // Ambil seluruh anggota untuk penandaan "tidak hadir" jika tidak diinput
        $allUserIds = User::pluck('id')->toArray();
        $absentUserIds = array_values(array_diff($allUserIds, $userIds));

        DB::transaction(function () use ($attendanceMap, $userIds, $absentUserIds, $notulensi, $bulan, $tahun, $createdBy) {
            // Tandai peserta yang diinput sesuai status (default hadir)
            foreach ($userIds as $userId) {
                $status = $attendanceMap[$userId] ?? Absen::STATUS_HADIR;

                $existing = Absen::where('user_id', $userId)
                    ->whereDate('tanggal', $notulensi->tanggal)
                    ->first();

                if ($existing) {
                    if ($existing->notulensi_id === $notulensi->id) {
                        $existing->status = $status;
                        $existing->keterangan = ucfirst($status) . ' (update dari Notulensi)';
                        $existing->save();
                    } else {
                        if (empty($existing->notulensi_id)) {
                            $existing->notulensi_id = $notulensi->id;
                            $existing->save();
                        }
                    }
                } else {
                    Absen::create([
                        'user_id' => $userId,
                        'tanggal' => $notulensi->tanggal,
                        'status' => $status,
                        'keterangan' => ucfirst($status) . ' (otomatis dari Notulensi)',
                        'notulensi_id' => $notulensi->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'created_by' => $createdBy,
                    ]);
                }
            }

            // Tandai anggota lain sebagai "tidak hadir" jika belum ada record pada tanggal rapat
            foreach ($absentUserIds as $userId) {
                $existing = Absen::where('user_id', $userId)
                    ->whereDate('tanggal', $notulensi->tanggal)
                    ->first();

                if ($existing) {
                    // Jangan menimpa status manual yang sudah ada
                    continue;
                }

                Absen::create([
                    'user_id' => $userId,
                    'tanggal' => $notulensi->tanggal,
                    'status' => Absen::STATUS_TIDAK_HADIR,
                    'keterangan' => 'Tidak hadir (otomatis dari Notulensi)',
                    'notulensi_id' => $notulensi->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'created_by' => $createdBy,
                ]);
            }
        });
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

