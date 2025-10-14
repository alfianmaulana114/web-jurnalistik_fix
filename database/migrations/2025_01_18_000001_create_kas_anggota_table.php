<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel kas_anggota untuk menyimpan data kas setiap anggota UKM Jurnalistik
     */
    public function up(): void
    {
        Schema::create('kas_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->decimal('jumlah_kas', 15, 2); // Jumlah kas yang harus dibayar
            $table->decimal('jumlah_terbayar', 15, 2)->default(0); // Jumlah yang sudah dibayar
            $table->decimal('sisa_kas', 15, 2)->default(0); // Sisa kas yang belum dibayar
            $table->enum('periode', ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember']); // Periode kas
            $table->year('tahun'); // Tahun kas
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas', 'terlambat'])->default('belum_bayar'); // Status pembayaran
            $table->date('tanggal_jatuh_tempo'); // Tanggal jatuh tempo pembayaran
            $table->date('tanggal_pembayaran')->nullable(); // Tanggal pembayaran terakhir
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // User yang membuat record (bendahara)
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // User yang terakhir update
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['user_id', 'periode', 'tahun']);
            $table->index(['status_pembayaran']);
            $table->index(['tanggal_jatuh_tempo']);
            
            // Unique constraint untuk mencegah duplikasi kas per user per periode per tahun
            $table->unique(['user_id', 'periode', 'tahun'], 'unique_kas_anggota_periode');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Menghapus tabel kas_anggota
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_anggota');
    }
};