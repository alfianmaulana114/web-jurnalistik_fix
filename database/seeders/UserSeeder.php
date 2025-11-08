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
        User::updateOrCreate(
            ['email' => 'koordinator@jurnalistik.com'],
            [
                'name' => 'Koordinator Jurnalistik',
                'password' => Hash::make('password'),
                'nim' => 12345678,
                'role' => User::ROLE_KOORDINATOR_JURNALISTIK,
            ]
        );

        // Sekretaris
        User::updateOrCreate(
            ['email' => 'sekretaris@jurnalistik.com'],
            [
                'name' => 'Sekretaris UKM',
                'password' => Hash::make('password'),
                'nim' => 12345679,
                'role' => User::ROLE_SEKRETARIS,
            ]
        );

        // Bendahara
        User::updateOrCreate(
            ['email' => 'bendahara@jurnalistik.com'],
            [
                'name' => 'Bendahara UKM',
                'password' => Hash::make('password'),
                'nim' => 12345680,
                'role' => User::ROLE_BENDAHARA,
            ]
        );

        // Koordinator Divisi Redaksi
        User::updateOrCreate(
            ['email' => 'koor.redaksi@jurnalistik.com'],
            [
                'name' => 'Koordinator Redaksi',
                'password' => Hash::make('password'),
                'nim' => 12345681,
                'role' => User::ROLE_KOORDINATOR_REDAKSI,
            ]
        );

        // Koordinator Divisi Litbang
        User::updateOrCreate(
            ['email' => 'koor.litbang@jurnalistik.com'],
            [
                'name' => 'Koordinator Litbang',
                'password' => Hash::make('password'),
                'nim' => 12345682,
                'role' => User::ROLE_KOORDINATOR_LITBANG,
            ]
        );

        // Koordinator Divisi Humas
        User::updateOrCreate(
            ['email' => 'koor.humas@jurnalistik.com'],
            [
                'name' => 'Koordinator Humas',
                'password' => Hash::make('password'),
                'nim' => 12345683,
                'role' => User::ROLE_KOORDINATOR_HUMAS,
            ]
        );

        // Koordinator Divisi Media Kreatif
        User::updateOrCreate(
            ['email' => 'koor.mediakreatif@jurnalistik.com'],
            [
                'name' => 'Koordinator Media Kreatif',
                'password' => Hash::make('password'),
                'nim' => 12345684,
                'role' => User::ROLE_KOORDINATOR_MEDIA_KREATIF,
            ]
        );

        // Anggota Divisi Redaksi
        User::updateOrCreate(
            ['email' => 'redaksi1@jurnalistik.com'],
            [
                'name' => 'Anggota Redaksi 1',
                'password' => Hash::make('password'),
                'nim' => 12345685,
                'role' => User::ROLE_ANGGOTA_REDAKSI,
            ]
        );

        User::updateOrCreate(
            ['email' => 'redaksi2@jurnalistik.com'],
            [
                'name' => 'Anggota Redaksi 2',
                'password' => Hash::make('password'),
                'nim' => 12345686,
                'role' => User::ROLE_ANGGOTA_REDAKSI,
            ]
        );

        // Anggota Divisi Litbang
        User::updateOrCreate(
            ['email' => 'litbang1@jurnalistik.com'],
            [
                'name' => 'Anggota Litbang 1',
                'password' => Hash::make('password'),
                'nim' => 12345687,
                'role' => User::ROLE_ANGGOTA_LITBANG,
            ]
        );

        User::updateOrCreate(
            ['email' => 'litbang2@jurnalistik.com'],
            [
                'name' => 'Anggota Litbang 2',
                'password' => Hash::make('password'),
                'nim' => 12345688,
                'role' => User::ROLE_ANGGOTA_LITBANG,
            ]
        );

        // Anggota Divisi Humas
        User::updateOrCreate(
            ['email' => 'humas1@jurnalistik.com'],
            [
                'name' => 'Anggota Humas 1',
                'password' => Hash::make('password'),
                'nim' => 12345689,
                'role' => User::ROLE_ANGGOTA_HUMAS,
            ]
        );

        User::updateOrCreate(
            ['email' => 'humas2@jurnalistik.com'],
            [
                'name' => 'Anggota Humas 2',
                'password' => Hash::make('password'),
                'nim' => 12345690,
                'role' => User::ROLE_ANGGOTA_HUMAS,
            ]
        );

        // Anggota Divisi Media Kreatif
        User::updateOrCreate(
            ['email' => 'mediakreatif1@jurnalistik.com'],
            [
                'name' => 'Anggota Media Kreatif 1',
                'password' => Hash::make('password'),
                'nim' => 12345691,
                'role' => User::ROLE_ANGGOTA_MEDIA_KREATIF,
            ]
        );

        User::updateOrCreate(
            ['email' => 'mediakreatif2@jurnalistik.com'],
            [
                'name' => 'Anggota Media Kreatif 2',
                'password' => Hash::make('password'),
                'nim' => 12345692,
                'role' => User::ROLE_ANGGOTA_MEDIA_KREATIF,
            ]
        );
    }
}