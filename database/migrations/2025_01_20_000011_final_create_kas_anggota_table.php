<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel kas_anggota
     */
    public function up(): void
    {
        Schema::create('kas_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('jumlah_terbayar', 15, 2)->default(0);
            $table->enum('periode', ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember']);
            $table->year('tahun');
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas', 'terlambat'])->default('belum_bayar');
            $table->date('tanggal_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['user_id', 'periode', 'tahun']);
            $table->index(['status_pembayaran']);
            
            // Unique constraint untuk mencegah duplikasi kas per user per periode per tahun
            $table->unique(['user_id', 'periode', 'tahun'], 'unique_kas_anggota_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_anggota');
    }
};

