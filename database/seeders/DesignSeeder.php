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
        
        $content1 = Content::first();
        $content2 = Content::skip(1)->first();
        $proker1 = Proker::first();
        $proker2 = Proker::skip(1)->first();

        // Design 1: Poster untuk Content (Published)
        Design::create([
            'judul' => 'Poster Berita Juara Robotika',
            'deskripsi' => 'Poster untuk mendukung publikasi berita tentang prestasi tim robotika universitas',
            'jenis_desain' => Design::TYPE_POSTER,
            'file_path' => 'designs/posters/poster-robotika-2025.jpg',
            'file_name' => 'poster-robotika-2025.jpg',
            'file_size' => 2048576, // 2MB
            'dimensi' => '1080x1350',
            'status' => Design::STATUS_PUBLISHED,
            'catatan_revisi' => 'Desain sudah sesuai dengan brand guideline',
            'content_id' => $content1->id,
            'proker_id' => null,
            'created_by' => $anggotaMediaKreatif1->id,
            'reviewed_by' => $koordinatorMediaKreatif->id,
        ]);

        // Design 2: Banner untuk Proker Workshop
        Design::create([
            'judul' => 'Banner Workshop Jurnalistik',
            'deskripsi' => 'Banner promosi untuk workshop jurnalistik pemula',
            'jenis_desain' => Design::TYPE_BANNER,
            'file_path' => 'designs/banners/banner-workshop-jurnalistik.png',
            'file_name' => 'banner-workshop-jurnalistik.png',
            'file_size' => 1536000, // 1.5MB
            'dimensi' => '1920x1080',
            'status' => Design::STATUS_PUBLISHED,
            'catatan_revisi' => null,
            'content_id' => null,
            'proker_id' => $proker1->id,
            'created_by' => $anggotaMediaKreatif2->id,
            'reviewed_by' => $koordinatorMediaKreatif->id,
        ]);

        // Design 3: Infografis dalam Review
        Design::create([
            'judul' => 'Infografis Dampak Kenaikan UKT',
            'deskripsi' => 'Infografis untuk menjelaskan dampak kenaikan UKT terhadap mahasiswa',
            'jenis_desain' => Design::TYPE_INFOGRAFIS,
            'file_path' => 'designs/infografis/infografis-ukt-2025.svg',
            'file_name' => 'infografis-ukt-2025.svg',
            'file_size' => 512000, // 500KB
            'dimensi' => '1080x1920',
            'status' => Design::STATUS_REVIEW,
            'catatan_revisi' => 'Perlu penyesuaian warna sesuai brand guideline',
            'content_id' => $content2->id,
            'proker_id' => null,
            'created_by' => $anggotaMediaKreatif1->id,
            'reviewed_by' => $koordinatorMediaKreatif->id,
        ]);

        // Design 4: Logo untuk Proker
        Design::create([
            'judul' => 'Logo Majalah Kampus Edisi Khusus',
            'deskripsi' => 'Logo khusus untuk majalah kampus edisi wisuda',
            'jenis_desain' => Design::TYPE_LOGO,
            'file_path' => 'designs/logos/logo-majalah-wisuda.ai',
            'file_name' => 'logo-majalah-wisuda.ai',
            'file_size' => 3072000, // 3MB
            'dimensi' => '2000x2000',
            'status' => Design::STATUS_APPROVED,
            'catatan_revisi' => 'Logo sudah final, siap untuk produksi',
            'content_id' => null,
            'proker_id' => $proker2->id,
            'created_by' => $koordinatorMediaKreatif->id,
            'reviewed_by' => $koordinatorMediaKreatif->id,
        ]);

        // Design 5: Flyer Draft
        Design::create([
            'judul' => 'Flyer Pelatihan Fotografi',
            'deskripsi' => 'Flyer promosi untuk pelatihan fotografi jurnalistik',
            'jenis_desain' => Design::TYPE_FLYER,
            'file_path' => 'designs/flyers/flyer-fotografi-draft.psd',
            'file_name' => 'flyer-fotografi-draft.psd',
            'file_size' => 4096000, // 4MB
            'dimensi' => '1080x1350',
            'status' => Design::STATUS_DRAFT,
            'catatan_revisi' => null,
            'content_id' => null,
            'proker_id' => Proker::skip(3)->first()->id,
            'created_by' => $anggotaMediaKreatif2->id,
            'reviewed_by' => null,
        ]);

        // Design 6: Thumbnail Video
        Design::create([
            'judul' => 'Thumbnail Video Tutorial Jurnalistik',
            'deskripsi' => 'Thumbnail untuk video tutorial dasar-dasar jurnalistik',
            'jenis_desain' => Design::TYPE_THUMBNAIL,
            'file_path' => 'designs/thumbnails/thumbnail-tutorial-jurnalistik.jpg',
            'file_name' => 'thumbnail-tutorial-jurnalistik.jpg',
            'file_size' => 1024000, // 1MB
            'dimensi' => '1280x720',
            'status' => Design::STATUS_PUBLISHED,
            'catatan_revisi' => 'Thumbnail menarik dan sesuai konten',
            'content_id' => null,
            'proker_id' => $proker1->id,
            'created_by' => $anggotaMediaKreatif1->id,
            'reviewed_by' => $koordinatorMediaKreatif->id,
        ]);

        // Design 7: Template Social Media
        Design::create([
            'judul' => 'Template Instagram Story UKM',
            'deskripsi' => 'Template untuk Instagram story UKM Jurnalistik',
            'jenis_desain' => Design::TYPE_TEMPLATE,
            'file_path' => 'designs/templates/ig-story-template.fig',
            'file_name' => 'ig-story-template.fig',
            'file_size' => 2560000, // 2.5MB
            'dimensi' => '1080x1920',
            'status' => Design::STATUS_APPROVED,
            'catatan_revisi' => 'Template sudah sesuai dengan brand identity UKM',
            'content_id' => null,
            'proker_id' => null,
            'created_by' => $koordinatorMediaKreatif->id,
            'reviewed_by' => $koordinatorMediaKreatif->id,
        ]);
    }
}