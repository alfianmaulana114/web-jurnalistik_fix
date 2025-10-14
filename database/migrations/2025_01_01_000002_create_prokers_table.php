<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel prokers untuk menyimpan program kerja UKM Jurnalistik
     */
    public function up(): void
    {
        if (!Schema::hasTable('prokers')) {
            Schema::create('prokers', function (Blueprint $table) {
                $table->id();
                $table->string('nama_proker');
                $table->text('deskripsi')->nullable();
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->enum('status', ['planning', 'ongoing', 'completed', 'cancelled'])->default('planning');
                $table->text('catatan')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Menghapus tabel prokers
     */
    public function down(): void
    {
        Schema::dropIfExists('prokers');
    }
};