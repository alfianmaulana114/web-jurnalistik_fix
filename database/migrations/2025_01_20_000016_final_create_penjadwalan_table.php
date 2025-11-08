<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel penjadwalan
     */
    public function up(): void
    {
        Schema::create('penjadwalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Anggota redaksi yang dijadwalkan
            $table->date('tanggal'); // Tanggal jadwal
            $table->text('keterangan')->nullable(); // Keterangan tambahan (opsional)
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Status jadwal
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Koordinator redaksi yang membuat jadwal
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('tanggal');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjadwalan');
    }
};

