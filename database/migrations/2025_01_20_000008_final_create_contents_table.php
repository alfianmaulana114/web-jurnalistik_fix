<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel contents dengan struktur final (caption system)
     */
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('caption');
            $table->enum('jenis_konten', ['caption_berita', 'caption_media_kreatif', 'caption_desain'])->default('caption_berita');
            $table->foreignId('brief_id')->nullable()->constrained('briefs')->onDelete('set null');
            $table->foreignId('berita_id')->nullable()->constrained('news')->onDelete('set null');
            $table->unsignedBigInteger('desain_id')->nullable();
            $table->string('platform_upload')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};

