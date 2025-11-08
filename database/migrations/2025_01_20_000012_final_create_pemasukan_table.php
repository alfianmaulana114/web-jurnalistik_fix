<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel pemasukan
     */
    public function up(): void
    {
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('sumber_pemasukan');
            $table->text('deskripsi');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_pemasukan');
            $table->enum('kategori', ['kas_anggota', 'donasi', 'sponsor', 'penjualan', 'hibah', 'lainnya'])->default('lainnya');
            $table->enum('metode_pembayaran', ['tunai', 'transfer_bank', 'e_wallet', 'cek', 'lainnya'])->default('tunai');
            $table->string('nomor_referensi')->nullable();
            $table->foreignId('kas_anggota_id')->nullable()->constrained('kas_anggota')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('bukti_pemasukan')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['tanggal_pemasukan']);
            $table->index(['kategori']);
            $table->index(['status']);
            $table->index(['sumber_pemasukan']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
    }
};

