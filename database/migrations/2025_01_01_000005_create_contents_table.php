<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel contents untuk menyimpan konten dari divisi redaksi
     */
    public function up(): void
    {
        if (!Schema::hasTable('contents')) {
            Schema::create('contents', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('konten');
                $table->enum('jenis_konten', ['artikel', 'berita', 'feature', 'opini', 'wawancara', 'reportase'])->default('artikel');
                $table->string('sumber')->nullable(); // Sumber berita atau referensi
                $table->text('catatan_editor')->nullable();
                $table->enum('status', ['draft', 'review', 'approved', 'published', 'rejected'])->default('draft');
                $table->foreignId('brief_id')->nullable()->constrained('briefs')->onDelete('set null'); // Relasi ke brief
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // User dari divisi redaksi
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // User yang mereview
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Menghapus tabel contents
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};