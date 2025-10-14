<?php

namespace Database\Seeders;

use App\Models\Brief;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BriefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data brief untuk divisi litbang
     */
    public function run(): void
    {
        // Brief 1: Kenaikan UKT
        Brief::create([
            'judul' => 'Kenaikan UKT Semester Genap 2025',
            'tanggal' => Carbon::now()->addDays(2),
            'isi_brief' => 'Universitas mengumumkan kenaikan UKT untuk semester genap 2025. Perlu investigasi mendalam tentang alasan kenaikan dan dampaknya terhadap mahasiswa. Fokus pada dampak kenaikan UKT terhadap mahasiswa kurang mampu dan program bantuan yang tersedia. Target narasumber: Wakil Rektor Bidang Keuangan, Ketua BEM, Mahasiswa penerima beasiswa.',
            'link_referensi' => 'https://www.universitas.ac.id/pengumuman-ukt-2025',
        ]);

        // Brief 2: Prestasi Mahasiswa
        Brief::create([
            'judul' => 'Prestasi Mahasiswa di Kompetisi Nasional',
            'tanggal' => Carbon::now()->addDays(5),
            'isi_brief' => 'Tim mahasiswa fakultas teknik meraih juara 1 dalam kompetisi robotika nasional. Perlu liputan mendalam tentang persiapan dan pencapaian mereka. Fokus pada proses persiapan tim, tantangan yang dihadapi, dan dampak prestasi bagi universitas. Target narasumber: Ketua tim, Dosen pembimbing, Dekan Fakultas Teknik.',
            'link_referensi' => 'https://kompetisi-robotika.com/hasil-2025',
        ]);

        // Brief 3: Program Magang
        Brief::create([
            'judul' => 'Program Magang Baru dengan Industri',
            'tanggal' => Carbon::now()->addDays(10),
            'isi_brief' => 'Universitas meluncurkan program magang baru dengan beberapa perusahaan teknologi terkemuka. Liputan tentang peluang dan manfaat program ini. Fokus pada peluang karir mahasiswa melalui program magang dan kesiapan industri. Target narasumber: Kepala Career Center, Perwakilan perusahaan partner, Mahasiswa peserta magang.',
            'link_referensi' => 'https://career.universitas.ac.id/program-magang-2025',
        ]);

        // Brief 4: Profil Dosen Berprestasi
        Brief::create([
            'judul' => 'Profil Dosen Berprestasi 2024',
            'tanggal' => Carbon::now()->addDays(15),
            'isi_brief' => 'Profil mendalam tentang dosen yang meraih penghargaan dosen berprestasi tingkat nasional tahun 2024. Fokus pada perjalanan karir, kontribusi dalam pendidikan, dan visi ke depan. Target narasumber: Dosen berprestasi, Rektor, Mahasiswa yang dibimbing. Feature story untuk edisi khusus majalah kampus.',
            'link_referensi' => 'https://kemendikbud.go.id/dosen-berprestasi-2024',
        ]);

        // Brief 5: Seminar Teknologi Berkelanjutan
        Brief::create([
            'judul' => 'Seminar Nasional Teknologi Berkelanjutan',
            'tanggal' => Carbon::now()->addDays(7),
            'isi_brief' => 'Liputan seminar nasional tentang teknologi berkelanjutan yang akan diselenggarakan oleh fakultas teknik. Fokus pada inovasi teknologi berkelanjutan dan peran universitas dalam pengembangan teknologi ramah lingkungan. Target narasumber: Keynote speaker, Panitia penyelenggara, Peserta seminar.',
            'link_referensi' => 'https://teknik.universitas.ac.id/seminar-teknologi-berkelanjutan',
        ]);
    }
}