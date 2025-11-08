<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel designs dengan struktur final (termasuk funfact)
     */
    public function up(): void
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('media_url')->nullable();
            $table->enum('jenis', ['desain', 'video', 'funfact'])->default('desain');
            $table->text('catatan')->nullable();
            $table->foreignId('berita_id')->nullable()->constrained('news')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        
        // Tambahkan foreign key ke tabel contents setelah tabel designs dibuat
        Schema::table('contents', function (Blueprint $table) {
            $table->foreign('desain_id')->references('id')->on('designs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};

