<?php

namespace App\Services\Bendahara;

use App\Models\KasAnggota;
use App\Models\User;
use App\Models\KasSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class KasAnggotaService
{
    public function getRiwayat(array $filters): array
    {
        $search = $filters['search'] ?? null;
        $tahun = (int) ($filters['tahun'] ?? now()->year);
        $divisi = $filters['divisi'] ?? null;

        $periodeOrder = [
            'januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'
        ];

        $usersQuery = User::where('role', '!=', 'admin');
        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        if ($divisi) {
            $usersQuery->where(function ($q) use ($divisi) {
                switch ($divisi) {
                    case 'redaksi':
                        $q->whereIn('role', [User::ROLE_KOORDINATOR_REDAKSI, User::ROLE_ANGGOTA_REDAKSI]);
                        break;
                    case 'litbang':
                        $q->whereIn('role', [User::ROLE_KOORDINATOR_LITBANG, User::ROLE_ANGGOTA_LITBANG]);
                        break;
                    case 'humas':
                        $q->whereIn('role', [User::ROLE_KOORDINATOR_HUMAS, User::ROLE_ANGGOTA_HUMAS]);
                        break;
                    case 'media_kreatif':
                        $q->whereIn('role', [User::ROLE_KOORDINATOR_MEDIA_KREATIF, User::ROLE_ANGGOTA_MEDIA_KREATIF]);
                        break;
                    case 'pengurus':
                        $q->whereIn('role', [User::ROLE_KOORDINATOR_JURNALISTIK, User::ROLE_SEKRETARIS, User::ROLE_BENDAHARA]);
                        break;
                }
            });
        }
        $users = $usersQuery->orderBy('name')->get();

        $kasRecords = KasAnggota::with('user')
            ->where('tahun', $tahun)
            ->get()
            ->groupBy('user_id');

        $riwayat = [];
        foreach ($users as $user) {
            $byMonth = [];
            foreach ($periodeOrder as $periode) {
                $userRecords = $kasRecords->get($user->id);
                $rec = $userRecords ? $userRecords->firstWhere('periode', $periode) : null;
                $byMonth[$periode] = [
                    'status' => $rec->status_pembayaran ?? 'belum_bayar',
                    'tanggal_pembayaran' => $rec ? $rec->tanggal_pembayaran : null,
                    'jumlah_terbayar' => (float) ($rec->jumlah_terbayar ?? 0),
                ];
            }
            $riwayat[$user->id] = [
                'user' => $user,
                'byMonth' => $byMonth,
            ];
        }

        $tahunOptions = KasAnggota::distinct()->pluck('tahun')->sortDesc()->values();

        return compact('riwayat', 'periodeOrder', 'tahunOptions', 'tahun', 'search', 'divisi');
    }

    public function store(Request $request): RedirectResponse
    {
        $maxKasAmount = KasSetting::getValue('jumlah_kas_anggota', 15000);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah_terbayar' => 'required|numeric|min:0|max:' . $maxKasAmount,
            'periode' => 'required|in:' . implode(',', array_keys(KasAnggota::getAllPeriode())),
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $existing = KasAnggota::where('user_id', $validated['user_id'])
                             ->where('periode', $validated['periode'])
                             ->where('tahun', $validated['tahun'])
                             ->first();
        if ($existing) {
            return back()->withErrors(['user_id' => 'Kas untuk anggota ini pada periode dan tahun yang sama sudah ada.'])
                         ->withInput();
        }

        $validated['created_by'] = Auth::id();
        $kasAnggota = KasAnggota::create($validated);
        $kasAnggota->updateStatusPembayaran();

        return redirect()->route('bendahara.kas-anggota.index')
            ->with('success', 'Data kas anggota berhasil ditambahkan.');
    }

    public function update(Request $request, KasAnggota $kasAnggota): RedirectResponse
    {
        $maxKasAmount = KasSetting::getValue('jumlah_kas_anggota', 15000);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah_terbayar' => 'required|numeric|min:0|max:' . $maxKasAmount,
            'periode' => 'required|in:' . implode(',', array_keys(KasAnggota::getAllPeriode())),
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'keterangan' => 'nullable|string|max:1000',
        ]);

        if ($kasAnggota->user_id != $validated['user_id'] ||
            $kasAnggota->periode != $validated['periode'] ||
            $kasAnggota->tahun != $validated['tahun']) {
            $existing = KasAnggota::where('user_id', $validated['user_id'])
                                 ->where('periode', $validated['periode'])
                                 ->where('tahun', $validated['tahun'])
                                 ->where('id', '!=', $kasAnggota->id)
                                 ->first();
            if ($existing) {
                return back()->withErrors(['user_id' => 'Kas untuk anggota ini pada periode dan tahun yang sama sudah ada.'])
                             ->withInput();
            }
        }

        $validated['updated_by'] = Auth::id();
        $kasAnggota->update($validated);
        $kasAnggota->updateStatusPembayaran();

        return redirect()->route('bendahara.kas-anggota.index')
            ->with('success', 'Data kas anggota berhasil diperbarui.');
    }

    public function destroy(KasAnggota $kasAnggota): RedirectResponse
    {
        if ($kasAnggota->pemasukan()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus kas anggota yang sudah memiliki riwayat pembayaran.');
        }
        $kasAnggota->delete();
        return redirect()->route('bendahara.kas-anggota.index')
            ->with('success', 'Data kas anggota berhasil dihapus.');
    }
}


