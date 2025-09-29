<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
    }

    public function down(): void
    {
        Schema::dropIfExists('news_genres');
        Schema::dropIfExists('news_types');
        Schema::dropIfExists('news_categories');
    }
};