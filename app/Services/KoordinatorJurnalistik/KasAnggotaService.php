<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\KasAnggota;
use App\Models\User;

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
}