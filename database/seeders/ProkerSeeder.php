<?php

namespace Database\Seeders;

use App\Models\Proker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data proker untuk UKM Jurnalistik
     */
    public function run(): void
    {
        $koordinatorJurnalistik = User::where('role', User::ROLE_KOORDINATOR_JURNALISTIK)->first();
        
        // Proker 1: Workshop Jurnalistik
        $proker1 = Proker::create([
            'nama_proker' => 'Workshop Jurnalistik untuk Pemula',
            'deskripsi' => 'Workshop dasar jurnalistik untuk mahasiswa baru yang ingin belajar menulis berita, wawancara, dan teknik jurnalistik lainnya.',
            'tanggal_mulai' => Carbon::now()->addDays(30),
            'tanggal_selesai' => Carbon::now()->addDays(32),
            'status' => Proker::STATUS_PLANNING,
            'catatan' => 'Perlu koordinasi dengan dosen pembimbing dan persiapan materi workshop',
            'created_by' => $koordinatorJurnalistik->id,
        ]);

        // Tambah panitia untuk proker 1
        $koordinatorRedaksi = User::where('role', User::ROLE_KOORDINATOR_REDAKSI)->first();
        $anggotaRedaksi = User::where('role', User::ROLE_ANGGOTA_REDAKSI)->first();
        
        $proker1->panitia()->attach($koordinatorRedaksi->id, [
            'jabatan_panitia' => 'Ketua Panitia',
            'tugas_khusus' => 'Mengkoordinir seluruh kegiatan workshop dan menyiapkan materi'
        ]);
        
        $proker1->panitia()->attach($anggotaRedaksi->id, [
            'jabatan_panitia' => 'Sekretaris',
            'tugas_khusus' => 'Mencatat peserta dan menyiapkan sertifikat'
        ]);

        // Proker 2: Peliputan Event Kampus
        $proker2 = Proker::create([
            'nama_proker' => 'Peliputan Event Dies Natalis Universitas',
            'deskripsi' => 'Peliputan komprehensif acara dies natalis universitas termasuk wawancara dengan pejabat kampus dan dokumentasi kegiatan.',
            'tanggal_mulai' => Carbon::now()->addDays(15),
            'tanggal_selesai' => Carbon::now()->addDays(17),
            'status' => Proker::STATUS_ONGOING,
            'catatan' => 'Sudah mendapat izin dari rektorat, tinggal koordinasi teknis',
            'created_by' => $koordinatorJurnalistik->id,
        ]);

        // Tambah panitia untuk proker 2
        $koordinatorLitbang = User::where('role', User::ROLE_KOORDINATOR_LITBANG)->first();
        $koordinatorHumas = User::where('role', User::ROLE_KOORDINATOR_HUMAS)->first();
        
        $proker2->panitia()->attach($koordinatorLitbang->id, [
            'jabatan_panitia' => 'Koordinator Liputan',
            'tugas_khusus' => 'Menyiapkan daftar narasumber dan angle berita'
        ]);
        
        $proker2->panitia()->attach($koordinatorHumas->id, [
            'jabatan_panitia' => 'Koordinator Publikasi',
            'tugas_khusus' => 'Mengelola publikasi hasil liputan di media sosial'
        ]);

        // Proker 3: Majalah Kampus Edisi Khusus
        $proker3 = Proker::create([
            'nama_proker' => 'Majalah Kampus Edisi Khusus Wisuda',
            'deskripsi' => 'Pembuatan majalah kampus edisi khusus untuk acara wisuda dengan tema prestasi mahasiswa dan alumni.',
            'tanggal_mulai' => Carbon::now()->addDays(45),
            'tanggal_selesai' => Carbon::now()->addDays(75),
            'status' => Proker::STATUS_PLANNING,
            'catatan' => 'Perlu budget untuk percetakan dan koordinasi dengan bagian kemahasiswaan',
            'created_by' => $koordinatorJurnalistik->id,
        ]);

        // Tambah panitia untuk proker 3
        $koordinatorMediaKreatif = User::where('role', User::ROLE_KOORDINATOR_MEDIA_KREATIF)->first();
        $sekretaris = User::where('role', User::ROLE_SEKRETARIS)->first();
        
        $proker3->panitia()->attach($koordinatorMediaKreatif->id, [
            'jabatan_panitia' => 'Koordinator Desain',
            'tugas_khusus' => 'Mendesain layout majalah dan cover'
        ]);
        
        $proker3->panitia()->attach($sekretaris->id, [
            'jabatan_panitia' => 'Koordinator Administrasi',
            'tugas_khusus' => 'Mengurus perizinan dan koordinasi dengan pihak kampus'
        ]);

        // Proker 4: Pelatihan Fotografi Jurnalistik
        Proker::create([
            'nama_proker' => 'Pelatihan Fotografi Jurnalistik',
            'deskripsi' => 'Pelatihan teknik fotografi untuk keperluan jurnalistik, termasuk foto berita, portrait, dan event coverage.',
            'tanggal_mulai' => Carbon::now()->subDays(10),
            'tanggal_selesai' => Carbon::now()->subDays(8),
            'status' => Proker::STATUS_COMPLETED,
            'catatan' => 'Berhasil diselenggarakan dengan 25 peserta, mendapat feedback positif',
            'created_by' => $koordinatorJurnalistik->id,
        ]);
    }
}