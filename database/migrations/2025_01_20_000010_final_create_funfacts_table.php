<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel funfacts
     */
    public function up(): void
    {
        Schema::create('funfacts', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->text('link_referensi')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funfacts');
    }
};

