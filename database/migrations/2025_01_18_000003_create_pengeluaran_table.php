<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel pengeluaran untuk menyimpan data pengeluaran keuangan UKM Jurnalistik
     */
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // Kode unik untuk setiap transaksi pengeluaran
            $table->string('keperluan'); // Keperluan pengeluaran
            $table->text('deskripsi'); // Deskripsi detail pengeluaran
            $table->decimal('jumlah', 15, 2); // Jumlah pengeluaran
            $table->date('tanggal_pengeluaran'); // Tanggal pengeluaran
            $table->enum('kategori', ['operasional', 'acara', 'peralatan', 'konsumsi', 'transport', 'administrasi', 'lainnya'])->default('lainnya'); // Kategori pengeluaran
            $table->enum('metode_pembayaran', ['tunai', 'transfer_bank', 'e_wallet', 'cek', 'lainnya'])->default('tunai'); // Metode pembayaran
            $table->string('nomor_referensi')->nullable(); // Nomor referensi (nomor transfer, nomor cek, dll)
            $table->string('penerima'); // Nama penerima/vendor/toko
            $table->string('bukti_pengeluaran')->nullable(); // Path file bukti pengeluaran (nota/kwitansi)
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending'); // Status approval dan pembayaran
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // User yang membuat record (bendahara)
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // User yang menyetujui
            $table->timestamp('approved_at')->nullable(); // Waktu approval
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null'); // User yang melakukan pembayaran
            $table->timestamp('paid_at')->nullable(); // Waktu pembayaran
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
     * 
     * Menghapus tabel pengeluaran
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};