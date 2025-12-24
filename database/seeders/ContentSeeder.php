<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Brief;
use App\Models\Design;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data konten untuk divisi redaksi
     */
    public function run(): void
    {
        $koordinatorRedaksi = User::where('role', User::ROLE_KOORDINATOR_REDAKSI)->first();
        $anggotaRedaksi1 = User::where('role', User::ROLE_ANGGOTA_REDAKSI)->first();
        $anggotaRedaksi2 = User::where('role', User::ROLE_ANGGOTA_REDAKSI)->skip(1)->first();
        
        if (!$koordinatorRedaksi || !$anggotaRedaksi1 || !$anggotaRedaksi2) {
            $this->command->warn('User Redaksi tidak ditemukan. Pastikan UserSeeder sudah dijalankan.');
            return;
        }
        
        $brief1 = Brief::first();
        $brief2 = Brief::skip(1)->first();
        $news1 = \App\Models\News::first();
        $news2 = \App\Models\News::skip(1)->first();
        $news3 = \App\Models\News::skip(2)->first();
        $design1 = Design::first();
        $design2 = Design::skip(1)->first();
        
        // Content 1: Caption Berita Published
        Content::updateOrCreate(
            ['judul' => 'Caption untuk Berita Robotika'],
            [
                'caption' => 'Tim robotika Universitas berhasil meraih juara pertama dalam kompetisi robotika nasional yang diselenggarakan di Jakarta. Tim yang terdiri dari 5 mahasiswa fakultas teknik ini berhasil mengalahkan 50 tim dari seluruh Indonesia.

Ketua tim, Ahmad Rizki, mengatakan bahwa persiapan untuk kompetisi ini sudah dilakukan sejak 6 bulan yang lalu. "Kami berlatih setiap hari dan mendapat bimbingan intensif dari dosen pembimbing," ujarnya.

Robot yang mereka ciptakan memiliki kemampuan untuk menyelesaikan berbagai tantangan yang diberikan dalam kompetisi, mulai dari navigasi otomatis hingga manipulasi objek.

Dekan Fakultas Teknik, Prof. Dr. Budi Santoso, mengapresiasi prestasi yang diraih mahasiswa. "Ini adalah bukti kualitas pendidikan teknik di universitas kita," katanya.',
                'jenis_konten' => Content::TYPE_CAPTION_BERITA,
                'brief_id' => $brief2 ? $brief2->id : null,
                'berita_id' => $news1 ? $news1->id : null,
                'created_by' => $anggotaRedaksi1->id,
                'published_at' => Carbon::now()->subDays(2),
            ]
        );

        // Content 2: Caption Berita dalam Review
        Content::updateOrCreate(
            ['judul' => 'Caption Dampak Kenaikan UKT'],
            [
                'caption' => 'Kenaikan Uang Kuliah Tunggal (UKT) untuk semester genap 2025 menimbulkan kekhawatiran di kalangan mahasiswa, terutama mereka yang berasal dari keluarga kurang mampu.

Berdasarkan data yang diperoleh, kenaikan UKT berkisar antara 10-15% untuk semua golongan. Hal ini tentu memberikan beban tambahan bagi mahasiswa dan orang tua.

Ketua BEM Universitas, Sarah Putri, menyatakan keprihatinannya. "Kenaikan ini sangat memberatkan mahasiswa, apalagi di tengah kondisi ekonomi yang belum sepenuhnya pulih," ungkapnya.

Wakil Rektor Bidang Keuangan, Dr. Andi Wijaya, menjelaskan bahwa kenaikan ini diperlukan untuk meningkatkan kualitas pendidikan dan fasilitas kampus. "Kami juga menyediakan berbagai program bantuan untuk mahasiswa yang membutuhkan," tambahnya.',
                'jenis_konten' => Content::TYPE_CAPTION_BERITA,
                'brief_id' => $brief1 ? $brief1->id : null,
                'berita_id' => $news2 ? $news2->id : null,
                'created_by' => $anggotaRedaksi2->id,
                'published_at' => null,
            ]
        );

        // Content 3: Caption Media Kreatif Draft
        if ($design1) {
            Content::updateOrCreate(
                ['judul' => 'Caption Inovasi Teknologi Berkelanjutan'],
                [
                    'caption' => 'Di tengah isu perubahan iklim global, mahasiswa Universitas menunjukkan kepedulian mereka melalui berbagai inovasi teknologi berkelanjutan.

Salah satu inovasi yang menarik perhatian adalah sistem pengolahan sampah organik menjadi biogas yang dikembangkan oleh mahasiswa teknik lingkungan. Sistem ini mampu mengolah 50 kg sampah organik per hari dan menghasilkan gas yang cukup untuk memasak selama 3 jam.

"Kami ingin memberikan solusi nyata untuk masalah sampah di lingkungan kampus," kata Rina Sari, ketua tim pengembang.

Selain itu, mahasiswa fakultas pertanian juga mengembangkan sistem hidroponik pintar yang dapat dikendalikan melalui smartphone. Sistem ini menggunakan sensor untuk memantau kondisi tanaman dan memberikan nutrisi secara otomatis.',
                    'jenis_konten' => Content::TYPE_CAPTION_MEDIA_KREATIF,
                    'desain_id' => $design1->id,
                    'created_by' => $anggotaRedaksi1->id,
                    'published_at' => null,
                ]
            );
        }

        // Content 4: Caption Editorial
        if ($design2) {
            Content::updateOrCreate(
                ['judul' => 'Caption Pentingnya Literasi Digital'],
                [
                    'caption' => 'Di era digital seperti sekarang ini, literasi digital bukan lagi menjadi pilihan, melainkan kebutuhan yang mendesak. Kemampuan untuk memahami, menggunakan, dan mengevaluasi informasi digital menjadi kunci sukses dalam berbagai aspek kehidupan.

Universitas sebagai lembaga pendidikan tinggi memiliki peran penting dalam meningkatkan literasi digital mahasiswa. Tidak hanya sebatas penggunaan teknologi, tetapi juga kemampuan berpikir kritis dalam menghadapi informasi yang tersebar di dunia maya.

Fenomena hoaks dan disinformasi yang marak terjadi menunjukkan betapa pentingnya kemampuan verifikasi informasi. Mahasiswa sebagai generasi muda harus mampu menjadi filter yang baik dalam menyebarkan informasi.

Oleh karena itu, kurikulum yang mengintegrasikan literasi digital perlu terus dikembangkan. Bukan hanya di fakultas teknologi, tetapi di semua fakultas tanpa terkecuali.',
                    'jenis_konten' => Content::TYPE_CAPTION_MEDIA_KREATIF,
                    'desain_id' => $design2->id,
                    'created_by' => $koordinatorRedaksi->id,
                    'published_at' => null,
                ]
            );
        }

        // Content 5: Caption Opini
        if ($news3) {
            Content::updateOrCreate(
                ['judul' => 'Caption Peran Mahasiswa dalam Pembangunan'],
                [
                    'caption' => 'Sebagai generasi penerus bangsa, mahasiswa memiliki peran strategis dalam mewujudkan pembangunan berkelanjutan. Konsep sustainable development yang mencakup aspek ekonomi, sosial, dan lingkungan harus menjadi perhatian serius mahasiswa.

Dalam aspek ekonomi, mahasiswa dapat berkontribusi melalui pengembangan usaha sosial (social enterprise) yang tidak hanya menguntungkan secara finansial, tetapi juga memberikan dampak positif bagi masyarakat.

Dari sisi sosial, mahasiswa dapat terlibat aktif dalam program-program kemasyarakatan yang bertujuan mengurangi kesenjangan dan meningkatkan kualitas hidup masyarakat.

Sementara untuk aspek lingkungan, mahasiswa dapat menjadi agen perubahan dalam kampanye pelestarian lingkungan dan pengembangan teknologi ramah lingkungan.

Universitas sebagai rumah kedua mahasiswa harus memberikan ruang dan dukungan untuk aktualisasi peran tersebut.',
                    'jenis_konten' => Content::TYPE_CAPTION_BERITA,
                    'berita_id' => $news3->id,
                    'created_by' => $anggotaRedaksi2->id,
                    'published_at' => null,
                ]
            );
        }
    }
}