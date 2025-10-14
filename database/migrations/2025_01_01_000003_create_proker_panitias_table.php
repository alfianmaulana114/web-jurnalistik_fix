<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel proker_panitias untuk menyimpan anggota panitia dalam setiap proker
     */
    public function up(): void
    {
        if (!Schema::hasTable('proker_panitias')) {
            Schema::create('proker_panitias', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proker_id')->constrained('prokers')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('jabatan_panitia'); // Ketua, Wakil Ketua, Sekretaris, Bendahara, Anggota, dll
                $table->text('tugas_khusus')->nullable(); // Tugas khusus dalam proker
                $table->timestamps();
                
                // Memastikan satu user tidak bisa memiliki jabatan yang sama dalam satu proker
                $table->unique(['proker_id', 'user_id', 'jabatan_panitia']);
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Menghapus tabel proker_panitias
     */
    public function down(): void
    {
        Schema::dropIfExists('proker_panitias');
    }
};