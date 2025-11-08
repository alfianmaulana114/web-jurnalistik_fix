<?php

namespace Database\Seeders;

use App\Models\Design;
use App\Models\Content;
use App\Models\Proker;
use App\Models\User;
use Illuminate\Database\Seeder;

class DesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data desain untuk divisi media kreatif
     */
    public function run(): void
    {
        $koordinatorMediaKreatif = User::where('role', User::ROLE_KOORDINATOR_MEDIA_KREATIF)->first();
        $anggotaMediaKreatif1 = User::where('role', User::ROLE_ANGGOTA_MEDIA_KREATIF)->first();
        $anggotaMediaKreatif2 = User::where('role', User::ROLE_ANGGOTA_MEDIA_KREATIF)->skip(1)->first();
        
        if (!$koordinatorMediaKreatif || !$anggotaMediaKreatif1 || !$anggotaMediaKreatif2) {
            $this->command->warn('User Media Kreatif tidak ditemukan. Pastikan UserSeeder sudah dijalankan.');
            return;
        }
        
        $proker1 = Proker::first();
        $proker2 = Proker::skip(1)->first();
        $proker4 = Proker::skip(3)->first();

        // Design 1: Desain untuk berita
        Design::updateOrCreate(
            ['judul' => 'Poster Berita Juara Robotika'],
            [
                'media_url' => 'https://example.com/poster-robotika.jpg',
                'jenis' => Design::JENIS_DESAIN,
                'catatan' => 'Poster untuk mendukung publikasi berita tentang prestasi tim robotika universitas',
                'berita_id' => null,
                'created_by' => $anggotaMediaKreatif1->id,
            ]
        );

        // Design 2: Video untuk proker
        if ($proker1) {
            Design::updateOrCreate(
                ['judul' => 'Video Workshop Jurnalistik'],
                [
                    'media_url' => 'https://example.com/video-workshop.mp4',
                    'jenis' => Design::JENIS_VIDEO,
                    'catatan' => 'Video promosi untuk workshop jurnalistik pemula',
                    'berita_id' => null,
                    'created_by' => $anggotaMediaKreatif2->id,
                ]
            );
        }

        // Design 3: Funfact
        Design::updateOrCreate(
            ['judul' => 'Funfact Teknologi Berkelanjutan'],
            [
                'media_url' => 'https://example.com/funfact-teknologi.jpg',
                'jenis' => Design::JENIS_FUNFACT,
                'catatan' => 'Funfact tentang inovasi teknologi berkelanjutan di kampus',
                'berita_id' => null,
                'created_by' => $anggotaMediaKreatif1->id,
            ]
        );

        // Design 4: Desain untuk proker
        if ($proker2) {
            Design::updateOrCreate(
                ['judul' => 'Logo Majalah Kampus Edisi Khusus'],
                [
                    'media_url' => 'https://example.com/logo-majalah.png',
                    'jenis' => Design::JENIS_DESAIN,
                    'catatan' => 'Logo khusus untuk majalah kampus edisi wisuda',
                    'berita_id' => null,
                    'created_by' => $koordinatorMediaKreatif->id,
                ]
            );
        }

        // Design 5: Video tutorial
        if ($proker1) {
            Design::updateOrCreate(
                ['judul' => 'Video Tutorial Jurnalistik'],
                [
                    'media_url' => 'https://example.com/tutorial-jurnalistik.mp4',
                    'jenis' => Design::JENIS_VIDEO,
                    'catatan' => 'Video tutorial dasar-dasar jurnalistik',
                    'berita_id' => null,
                    'created_by' => $anggotaMediaKreatif1->id,
                ]
            );
        }
    }
}