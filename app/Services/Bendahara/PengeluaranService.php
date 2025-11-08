<?php

namespace App\Services\Bendahara;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class PengeluaranService
{
    public function getIndexData(array $filters): array
    {
        $query = Pengeluaran::with(['creator', 'approver', 'payer']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }
        if (!empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_pengeluaran', '>=', $filters['tanggal_mulai']);
        }
        if (!empty($filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_pengeluaran', '<=', $filters['tanggal_selesai']);
        }
        if (!empty($filters['bulan'])) {
            $query->whereMonth('tanggal_pengeluaran', $filters['bulan']);
        }
        if (!empty($filters['tahun'])) {
            $query->whereYear('tanggal_pengeluaran', $filters['tahun']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%");
            });
        }

        $pengeluaran = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $kategoriOptions = Pengeluaran::getAllKategori();
        $statusOptions = Pengeluaran::getAllStatus();

        return compact('pengeluaran', 'kategoriOptions', 'statusOptions');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'keperluan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'kategori' => 'required|in:' . implode(',', array_keys(Pengeluaran::getAllKategori())),
            'metode_pembayaran' => 'required|in:' . implode(',', array_keys(Pengeluaran::getAllMetodePembayaran())),
            'nomor_referensi' => 'nullable|string|max:100',
            'penerima' => 'required|string|max:255',
            'bukti_pengeluaran' => 'nullable|url|max:1000',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = Auth::id();

        // Bukti pengeluaran sekarang berupa URL, tidak ada upload file.

        Pengeluaran::create($validated);

        return redirect()->route('bendahara.pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, Pengeluaran $pengeluaran): RedirectResponse
    {
        $validated = $request->validate([
            'keperluan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'kategori' => 'required|in:' . implode(',', array_keys(Pengeluaran::getAllKategori())),
            'metode_pembayaran' => 'required|in:' . implode(',', array_keys(Pengeluaran::getAllMetodePembayaran())),
            'nomor_referensi' => 'nullable|string|max:100',
            'penerima' => 'required|string|max:255',
            'bukti_pengeluaran' => 'nullable|url|max:1000',
            'keterangan' => 'nullable|string|max:1000',
        ]);
        // Tidak ada pengelolaan file saat update karena bukti berupa URL.

        $pengeluaran->update($validated);

        return redirect()->route('bendahara.pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran): RedirectResponse
    {
        if ($pengeluaran->bukti_pengeluaran && !filter_var($pengeluaran->bukti_pengeluaran, FILTER_VALIDATE_URL)) {
            if (Storage::disk('public')->exists($pengeluaran->bukti_pengeluaran)) {
                Storage::disk('public')->delete($pengeluaran->bukti_pengeluaran);
            }
        }
        $pengeluaran->delete();
        return redirect()->route('bendahara.pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    public function approve(Pengeluaran $pengeluaran): RedirectResponse
    {
        if ($pengeluaran->isApproved()) {
            return back()->with('error', 'Pengeluaran sudah disetujui sebelumnya.');
        }
        $pengeluaran->update([
            'status' => Pengeluaran::STATUS_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Pengeluaran berhasil disetujui.');
    }

    public function pay(Pengeluaran $pengeluaran): RedirectResponse
    {
        if (!$pengeluaran->isApproved()) {
            return back()->with('error', 'Pengeluaran harus disetujui terlebih dahulu.');
        }
        if ($pengeluaran->isPaid()) {
            return back()->with('error', 'Pengeluaran sudah dibayar sebelumnya.');
        }
        $pengeluaran->update([
            'status' => Pengeluaran::STATUS_PAID,
            'paid_by' => Auth::id(),
            'paid_at' => now(),
        ]);
        return back()->with('success', 'Pengeluaran berhasil ditandai sebagai dibayar.');
    }
}


