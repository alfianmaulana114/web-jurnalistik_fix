<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel designs untuk menyimpan desain dari divisi media kreatif
     */
    public function up(): void
    {
        if (!Schema::hasTable('designs')) {
            Schema::create('designs', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi');
                $table->enum('jenis_desain', ['poster', 'banner', 'infografis', 'logo', 'thumbnail', 'cover', 'ilustrasi', 'video'])->default('poster');
                $table->string('file_path'); // Path file desain
                $table->string('file_name'); // Nama file asli
                $table->string('file_size')->nullable(); // Ukuran file
                $table->string('dimensi')->nullable(); // Dimensi desain (contoh: 1920x1080)
                $table->enum('status', ['draft', 'review', 'approved', 'published', 'rejected'])->default('draft');
                $table->text('catatan_revisi')->nullable();
                $table->foreignId('content_id')->nullable()->constrained('contents')->onDelete('set null'); // Relasi ke konten
                $table->foreignId('proker_id')->nullable()->constrained('prokers')->onDelete('set null'); // Relasi ke proker
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // User dari divisi media kreatif
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // User yang mereview
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Menghapus tabel designs
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};