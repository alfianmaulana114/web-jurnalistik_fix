<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Merombak tabel contents untuk sistem caption berdasarkan berita redaksi dan media kreatif
     */
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            // Ubah enum jenis_konten untuk caption saja
            $table->dropColumn('jenis_konten');
        });
        
        Schema::table('contents', function (Blueprint $table) {
            // Tambah jenis caption yang baru
            $table->enum('jenis_konten', ['caption_berita', 'caption_media_kreatif'])->default('caption_berita')->after('konten');
            
            // Tambah kolom untuk referensi media
            $table->string('media_type')->nullable()->after('jenis_konten')->comment('foto, video untuk media kreatif');
            $table->string('media_path')->nullable()->after('media_type')->comment('Path file media untuk caption');
            $table->text('media_description')->nullable()->after('media_path')->comment('Deskripsi media yang di-caption');
            
            // Ubah nama kolom konten menjadi caption
            $table->renameColumn('konten', 'caption');
            
            // Tambah kolom untuk referensi berita asli (untuk caption berita)
            $table->text('berita_referensi')->nullable()->after('caption')->comment('Referensi berita asli untuk caption berita');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Mengembalikan struktur tabel contents ke bentuk semula
     */
    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['media_type', 'media_path', 'media_description', 'berita_referensi']);
            
            // Kembalikan nama kolom
            $table->renameColumn('caption', 'konten');
            
            // Ubah kembali enum jenis_konten
            $table->dropColumn('jenis_konten');
        });
        
        Schema::table('contents', function (Blueprint $table) {
            $table->enum('jenis_konten', ['artikel', 'berita', 'feature', 'opini', 'wawancara', 'reportase'])->default('artikel')->after('konten');
        });
    }
};