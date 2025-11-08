<?php

namespace App\Services\Bendahara;

use App\Models\Pemasukan;
use App\Models\KasAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class PemasukanService
{
    public function getIndexData(array $filters): array
    {
        $query = Pemasukan::with('user')->latest('tanggal_pemasukan');

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['bulan'])) {
            $query->whereMonth('tanggal_pemasukan', $filters['bulan']);
        }
        if (!empty($filters['tahun'])) {
            $query->whereYear('tanggal_pemasukan', $filters['tahun']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('sumber', 'like', "%{$search}%");
            });
        }

        $pemasukan = $query->paginate(10)->withQueryString();

        $statistics = [
            'total'      => Pemasukan::verified()->sum('jumlah'),
            'verified'   => Pemasukan::verified()->count(),
            'pending'    => Pemasukan::pending()->count(),
            'this_month' => Pemasukan::verified()
                                ->whereYear('tanggal_pemasukan', now()->year)
                                ->whereMonth('tanggal_pemasukan', now()->month)
                                ->sum('jumlah'),
        ];

        return compact('pemasukan', 'statistics');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sumber_pemasukan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_pemasukan' => 'required|date',
            'kategori' => 'required|in:' . implode(',', array_keys(Pemasukan::getAllKategori())),
            'metode_pembayaran' => 'required|in:' . implode(',', array_keys(Pemasukan::getAllMetodePembayaran())),
            'nomor_referensi' => 'nullable|string|max:100',
            'kas_anggota_id' => 'nullable|exists:kas_anggota,id',
            'bukti_pemasukan' => 'nullable|url|max:1000',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['created_by'] = Auth::id();

        // Bukti pemasukan sekarang berupa URL, tidak perlu penyimpanan file.

        $pemasukan = Pemasukan::create($validated);

        if ($pemasukan->kas_anggota_id && $pemasukan->kategori === 'kas_anggota') {
            $kasAnggota = KasAnggota::find($pemasukan->kas_anggota_id);
            if ($kasAnggota) {
                $kasAnggota->jumlah_terbayar += $pemasukan->jumlah;
                $kasAnggota->tanggal_pembayaran = $pemasukan->tanggal_pemasukan;
                $kasAnggota->updateStatusPembayaran();
                $kasAnggota->save();
            }
        }

        return redirect()->route('bendahara.pemasukan.index')
            ->with('success', 'Data pemasukan berhasil ditambahkan.');
    }

    public function update(Request $request, Pemasukan $pemasukan): RedirectResponse
    {
        $validated = $request->validate([
            'sumber_pemasukan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_pemasukan' => 'required|date',
            'kategori' => 'required|in:' . implode(',', array_keys(Pemasukan::getAllKategori())),
            'metode_pembayaran' => 'required|in:' . implode(',', array_keys(Pemasukan::getAllMetodePembayaran())),
            'nomor_referensi' => 'nullable|string|max:100',
            'kas_anggota_id' => 'nullable|exists:kas_anggota,id',
            'bukti_pemasukan' => 'nullable|url|max:1000',
            'keterangan' => 'nullable|string|max:1000',
        ]);
        // Bukti pemasukan berupa URL, tidak ada pengelolaan file saat update.

        $pemasukan->update($validated);

        return redirect()->route('bendahara.pemasukan.index')
            ->with('success', 'Data pemasukan berhasil diperbarui.');
    }

    public function destroy(Pemasukan $pemasukan): RedirectResponse
    {
        if ($pemasukan->bukti_pemasukan && !filter_var($pemasukan->bukti_pemasukan, FILTER_VALIDATE_URL)) {
            if (Storage::disk('public')->exists($pemasukan->bukti_pemasukan)) {
                Storage::disk('public')->delete($pemasukan->bukti_pemasukan);
            }
        }

        if ($pemasukan->kas_anggota_id && $pemasukan->kategori === 'kas_anggota') {
            $kasAnggota = KasAnggota::find($pemasukan->kas_anggota_id);
            if ($kasAnggota) {
                $kasAnggota->jumlah_terbayar -= $pemasukan->jumlah;
                $kasAnggota->updateStatusPembayaran();
                $kasAnggota->save();
            }
        }

        $pemasukan->delete();

        return redirect()->route('bendahara.pemasukan.index')
            ->with('success', 'Data pemasukan berhasil dihapus.');
    }

    public function verify(Pemasukan $pemasukan): RedirectResponse
    {
        if ($pemasukan->isVerified()) {
            return back()->with('error', 'Pemasukan sudah diverifikasi sebelumnya.');
        }

        $pemasukan->update([
            'status' => Pemasukan::STATUS_VERIFIED,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pemasukan berhasil diverifikasi.');
    }
}


