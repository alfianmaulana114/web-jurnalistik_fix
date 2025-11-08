<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel pengeluaran
     */
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('keperluan');
            $table->text('deskripsi');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_pengeluaran');
            $table->enum('kategori', ['operasional', 'acara', 'peralatan', 'konsumsi', 'transport', 'administrasi', 'lainnya'])->default('lainnya');
            $table->enum('metode_pembayaran', ['tunai', 'transfer_bank', 'e_wallet', 'cek', 'lainnya'])->default('tunai');
            $table->string('nomor_referensi')->nullable();
            $table->string('penerima');
            $table->string('bukti_pengeluaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['tanggal_pengeluaran']);
            $table->index(['kategori']);
            $table->index(['status']);
            $table->index(['keperluan']);
            $table->index(['created_by']);
            $table->index(['penerima']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};

