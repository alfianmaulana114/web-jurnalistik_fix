<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data user untuk semua role dalam UKM Jurnalistik
     */
    public function run(): void
    {
        // Koordinator Jurnalistik
        User::create([
            'name' => 'Koordinator Jurnalistik',
            'email' => 'koordinator@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345678,
            'role' => User::ROLE_KOORDINATOR_JURNALISTIK,
        ]);

        // Sekretaris
        User::create([
            'name' => 'Sekretaris UKM',
            'email' => 'sekretaris@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345679,
            'role' => User::ROLE_SEKRETARIS,
        ]);

        // Bendahara
        User::create([
            'name' => 'Bendahara UKM',
            'email' => 'bendahara@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345680,
            'role' => User::ROLE_BENDAHARA,
        ]);

        // Koordinator Divisi Redaksi
        User::create([
            'name' => 'Koordinator Redaksi',
            'email' => 'koor.redaksi@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345681,
            'role' => User::ROLE_KOORDINATOR_REDAKSI,
        ]);

        // Koordinator Divisi Litbang
        User::create([
            'name' => 'Koordinator Litbang',
            'email' => 'koor.litbang@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345682,
            'role' => User::ROLE_KOORDINATOR_LITBANG,
        ]);

        // Koordinator Divisi Humas
        User::create([
            'name' => 'Koordinator Humas',
            'email' => 'koor.humas@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345683,
            'role' => User::ROLE_KOORDINATOR_HUMAS,
        ]);

        // Koordinator Divisi Media Kreatif
        User::create([
            'name' => 'Koordinator Media Kreatif',
            'email' => 'koor.mediakreatif@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345684,
            'role' => User::ROLE_KOORDINATOR_MEDIA_KREATIF,
        ]);

        // Anggota Divisi Redaksi
        User::create([
            'name' => 'Anggota Redaksi 1',
            'email' => 'redaksi1@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345685,
            'role' => User::ROLE_ANGGOTA_REDAKSI,
        ]);

        User::create([
            'name' => 'Anggota Redaksi 2',
            'email' => 'redaksi2@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345686,
            'role' => User::ROLE_ANGGOTA_REDAKSI,
        ]);

        // Anggota Divisi Litbang
        User::create([
            'name' => 'Anggota Litbang 1',
            'email' => 'litbang1@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345687,
            'role' => User::ROLE_ANGGOTA_LITBANG,
        ]);

        User::create([
            'name' => 'Anggota Litbang 2',
            'email' => 'litbang2@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345688,
            'role' => User::ROLE_ANGGOTA_LITBANG,
        ]);

        // Anggota Divisi Humas
        User::create([
            'name' => 'Anggota Humas 1',
            'email' => 'humas1@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345689,
            'role' => User::ROLE_ANGGOTA_HUMAS,
        ]);

        User::create([
            'name' => 'Anggota Humas 2',
            'email' => 'humas2@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345690,
            'role' => User::ROLE_ANGGOTA_HUMAS,
        ]);

        // Anggota Divisi Media Kreatif
        User::create([
            'name' => 'Anggota Media Kreatif 1',
            'email' => 'mediakreatif1@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345691,
            'role' => User::ROLE_ANGGOTA_MEDIA_KREATIF,
        ]);

        User::create([
            'name' => 'Anggota Media Kreatif 2',
            'email' => 'mediakreatif2@jurnalistik.com',
            'password' => Hash::make('password'),
            'nim' => 12345692,
            'role' => User::ROLE_ANGGOTA_MEDIA_KREATIF,
        ]);
    }
}