<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel proker_panitias
     */
    public function up(): void
    {
        Schema::create('proker_panitias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proker_id')->constrained('prokers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jabatan_panitia');
            $table->text('tugas_khusus')->nullable();
            $table->timestamps();
            
            // Memastikan satu user tidak bisa memiliki jabatan yang sama dalam satu proker
            $table->unique(['proker_id', 'user_id', 'jabatan_panitia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proker_panitias');
    }
};

