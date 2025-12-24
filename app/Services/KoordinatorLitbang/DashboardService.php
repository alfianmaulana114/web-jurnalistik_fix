<?php

namespace App\Services\KoordinatorLitbang;

use App\Models\User;
use App\Models\Brief;
use App\Models\News;
use App\Models\KasAnggota;

class DashboardService
{
    private function safeCount(string $modelClass): int
    {
        try {
            return $modelClass::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function safeQuery(callable $callback, $default = null)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $default;
        }
    }

    public function getDashboardData(): array
    {
        $litbangStats = $this->getLitbangStats();
        $briefStats = $this->getBriefStats();
        $newsStats = $this->getNewsStats();
        $kasStats = $this->getKasStats();
        $recentData = $this->getRecentData();

        return array_merge(
            $litbangStats,
            $briefStats,
            $newsStats,
            $kasStats,
            $recentData,
        );
    }

    private function getLitbangStats(): array
    {
        return [
            'litbang_coordinators' => User::where('role', User::ROLE_KOORDINATOR_LITBANG)->count(),
            'litbang_members' => User::where('role', User::ROLE_ANGGOTA_LITBANG)->count(),
            'litbang_total' => User::whereIn('role', [User::ROLE_KOORDINATOR_LITBANG, User::ROLE_ANGGOTA_LITBANG])->count(),
        ];
    }

    private function getBriefStats(): array
    {
        $total = $this->safeCount(Brief::class);
        $withRef = $this->safeQuery(function () { return Brief::whereNotNull('link_referensi')->where('link_referensi', '!=', '')->count(); }, 0);
        $withoutRef = max(0, $total - $withRef);
        $thisMonth = $this->safeQuery(function () { return Brief::whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->count(); }, 0);

        return [
            'brief_total' => $total,
            'brief_with_ref' => $withRef,
            'brief_without_ref' => $withoutRef,
            'brief_this_month' => $thisMonth,
        ];
    }

    private function getNewsStats(): array
    {
        $totalLitbangNews = $this->safeQuery(function () {
            return News::whereHas('user', function ($q) {
                $q->whereIn('role', [User::ROLE_KOORDINATOR_LITBANG, User::ROLE_ANGGOTA_LITBANG]);
            })->count();
        }, 0);

        return [
            'news_total_litbang' => $totalLitbangNews,
        ];
    }

    private function getKasStats(): array
    {
        $litbangUsers = User::whereIn('role', [User::ROLE_KOORDINATOR_LITBANG, User::ROLE_ANGGOTA_LITBANG])->pluck('id');

        $totalKasRecords = $this->safeQuery(function () use ($litbangUsers) {
            return KasAnggota::whereIn('user_id', $litbangUsers)->count();
        }, 0);

        $kasLunas = $this->safeQuery(function () use ($litbangUsers) {
            return KasAnggota::whereIn('user_id', $litbangUsers)
                ->where('status_pembayaran', KasAnggota::STATUS_LUNAS)
                ->count();
        }, 0);

        $kasBelumLunas = $this->safeQuery(function () use ($litbangUsers) {
            return KasAnggota::whereIn('user_id', $litbangUsers)
                ->whereNotIn('status_pembayaran', [KasAnggota::STATUS_LUNAS])
                ->count();
        }, 0);

        $totalTerkumpul = $this->safeQuery(function () use ($litbangUsers) {
            return (float) KasAnggota::whereIn('user_id', $litbangUsers)
                ->where('status_pembayaran', KasAnggota::STATUS_LUNAS)
                ->sum('jumlah_terbayar');
        }, 0);

        return [
            'kas_total_records' => $totalKasRecords,
            'kas_lunas' => $kasLunas,
            'kas_belum_lunas' => $kasBelumLunas,
            'kas_total_terkumpul' => $totalTerkumpul,
        ];
    }

    private function getRecentData(): array
    {
        $recentBriefs = $this->safeQuery(function () {
            return Brief::latest('tanggal')->latest()->take(8)->get();
        }, collect());

        $recentNews = $this->safeQuery(function () {
            return News::with('user')->latest()->take(5)->get();
        }, collect());

        return [
            'recent_briefs' => $recentBriefs,
            'recent_news' => $recentNews,
        ];
    }
}