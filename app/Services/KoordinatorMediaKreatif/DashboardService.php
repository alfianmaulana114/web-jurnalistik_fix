<?php

namespace App\Services\KoordinatorMediaKreatif;

use App\Models\User;
use App\Models\Design;
use App\Models\Content;
use App\Models\KasAnggota;

/**
 * Service untuk Dashboard Koordinator Media Kreatif
 * 
 * Service ini menangani semua logika bisnis untuk dashboard koordinator media kreatif,
 * termasuk statistik anggota, desain, caption, dan kas.
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
        // Get users in media kreatif division
        $users = User::whereIn('role', [
            User::ROLE_KOORDINATOR_MEDIA_KREATIF,
            User::ROLE_ANGGOTA_MEDIA_KREATIF
        ])->get();
        
        // Get design statistics
        $design_total = Design::count();
        $designs = Design::latest()->take(5)->get();
        
        // Get caption statistics
        $captions_total = Content::where(function($query) {
            $query->where('jenis_konten', Content::TYPE_CAPTION_DESAIN)
                  ->orWhere('jenis_konten', Content::TYPE_CAPTION_MEDIA_KREATIF);
        })->count();
        
        // Get kas statistics for media kreatif division
        $mediaKreatifUserIds = $users->pluck('id');
        
        $kas_lunas = KasAnggota::whereIn('user_id', $mediaKreatifUserIds)
            ->where('status_pembayaran', KasAnggota::STATUS_LUNAS)
            ->count();
        
        $kas_total_records = KasAnggota::whereIn('user_id', $mediaKreatifUserIds)->count();
        
        $kas_belum_lunas = KasAnggota::whereIn('user_id', $mediaKreatifUserIds)
            ->where('status_pembayaran', KasAnggota::STATUS_BELUM_BAYAR)
            ->count();
        
        $kas_total_terkumpul = KasAnggota::whereIn('user_id', $mediaKreatifUserIds)
            ->sum('jumlah_terbayar');
        
        return [
            'users' => $users,
            'design_total' => $design_total,
            'designs' => $designs,
            'captions_total' => $captions_total,
            'kas_lunas' => $kas_lunas,
            'kas_total_records' => $kas_total_records,
            'kas_belum_lunas' => $kas_belum_lunas,
            'kas_total_terkumpul' => $kas_total_terkumpul,
        ];
    }
}

