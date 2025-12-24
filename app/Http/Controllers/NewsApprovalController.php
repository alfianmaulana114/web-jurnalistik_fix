<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsApproval;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class NewsApprovalController extends Controller
{
    /**
     * Menyetujui berita berdasarkan ID.
     *
     * Memastikan user memiliki role yang berhak, membuat record persetujuan,
     * dan memberikan umpan balik yang sesuai.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function approve(int $id): RedirectResponse
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, [
            User::ROLE_KOORDINATOR_JURNALISTIK,
            User::ROLE_KOORDINATOR_REDAKSI,
            User::ROLE_ANGGOTA_REDAKSI,
        ])) {
            abort(403, 'Tidak memiliki akses persetujuan berita');
        }

        $news = News::findOrFail($id);

        $existing = NewsApproval::where('news_id', $news->id)->first();
        if ($existing) {
            if ($existing->user_id === $user->id) {
                return back()->with('success', 'Berita sudah Anda setujui');
            }
            $approverName = $existing->user?->name ?? 'Pengguna';
            return back()->with('error', 'Berita sudah disetujui oleh ' . $approverName);
        }

        NewsApproval::create([
            'news_id' => $news->id,
            'user_id' => $user->id,
            'approved_at' => Carbon::now(),
            'note' => null,
        ]);

        return back()->with('success', 'Berita disetujui oleh ' . ($user->name ?? 'Pengguna'));
    }

    /**
     * Membatalkan persetujuan berita.
     *
     * Saat ini pembatalan tidak didukung dan akan mengembalikan pesan error.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function revoke(int $id): RedirectResponse
    {
        return back()->with('error', 'Persetujuan tidak dapat dibatalkan');
    }
}