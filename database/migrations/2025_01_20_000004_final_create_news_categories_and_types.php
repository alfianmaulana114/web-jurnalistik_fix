<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel news_categories, news_types, dan news_genres
     */
    public function up(): void
    {
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['Berita Nasional', 'Berita Internasional', 'Berita Internal']);
            $table->timestamps();
        });

        Schema::create('news_types', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['Berita Harian', 'Berita Terkini', 'Press Release', 'Media Partner']);
            $table->timestamps();
        });

        Schema::create('news_genres', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['Hiburan', 'Inspirasi', 'Nasional', 'Olahraga', 'Peristiwa', 'Politik', 'Teknologi', 'UBSI']);
            $table->timestamps();
        });

        Schema::create('news_genre_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_genre_id')->constrained('news_genres')->onDelete('cascade');
            $table->timestamps();
        });
        
        // Tambahkan foreign key ke tabel news setelah tabel categories dan types dibuat
        Schema::table('news', function (Blueprint $table) {
            $table->foreign('news_category_id')->references('id')->on('news_categories')->onDelete('set null');
            $table->foreign('news_type_id')->references('id')->on('news_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_genre_pivot');
        Schema::dropIfExists('news_genres');
        Schema::dropIfExists('news_types');
        Schema::dropIfExists('news_categories');
    }
};

