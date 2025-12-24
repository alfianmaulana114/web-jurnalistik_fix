<?php

namespace App\Services\KoordinatorHumas;

use App\Models\User;
use App\Models\Content;
use App\Models\KasAnggota;

/**
 * Service untuk Dashboard Koordinator Humas
 * 
 * Service ini menangani semua logika bisnis untuk dashboard koordinator humas,
 * termasuk statistik anggota, content, dan kas.
 */
class DashboardService
{
    /**
     * Mendapatkan semua data untuk dashboard
     * 
     * @return array Data dashboard
     */
    public function getDashboardData(): array
    {
        // Get users in humas division
        $users = User::whereIn('role', [
            User::ROLE_KOORDINATOR_HUMAS,
            User::ROLE_ANGGOTA_HUMAS
        ])->get();
        
        // Get content statistics
        $content_total = Content::where('jenis_konten', Content::TYPE_CAPTION_MEDIA_KREATIF)->count();
        $contents = Content::where('jenis_konten', Content::TYPE_CAPTION_MEDIA_KREATIF)
            ->latest()
            ->take(5)
            ->get();
        
        // Get kas statistics for humas division
        $humasUserIds = $users->pluck('id');
        
        $kas_lunas = KasAnggota::whereIn('user_id', $humasUserIds)
            ->where('status_pembayaran', KasAnggota::STATUS_LUNAS)
            ->count();
        
        $kas_total_records = KasAnggota::whereIn('user_id', $humasUserIds)->count();
        
        $kas_belum_lunas = KasAnggota::whereIn('user_id', $humasUserIds)
            ->where('status_pembayaran', KasAnggota::STATUS_BELUM_BAYAR)
            ->count();
        
        $kas_total_terkumpul = KasAnggota::whereIn('user_id', $humasUserIds)
            ->sum('jumlah_terbayar');
        
        return [
            'users' => $users,
            'content_total' => $content_total,
            'contents' => $contents,
            'kas_lunas' => $kas_lunas,
            'kas_total_records' => $kas_total_records,
            'kas_belum_lunas' => $kas_belum_lunas,
            'kas_total_terkumpul' => $kas_total_terkumpul,
        ];
    }
}

