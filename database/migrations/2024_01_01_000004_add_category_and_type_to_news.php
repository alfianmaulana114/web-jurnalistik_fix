<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->foreignId('news_category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('news_type_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::create('news_genre_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_genres_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['news_category_id']);
            $table->dropForeign(['news_type_id']);
            $table->dropColumn(['news_category_id', 'news_type_id']);
        });

        Schema::dropIfExists('news_genre_pivot');
    }
};