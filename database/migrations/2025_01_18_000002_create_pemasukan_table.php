<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel pemasukan untuk menyimpan data pemasukan keuangan UKM Jurnalistik
     */
    public function up(): void
    {
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // Kode unik untuk setiap transaksi pemasukan
            $table->string('sumber_pemasukan'); // Sumber pemasukan (kas anggota, donasi, sponsor, dll)
            $table->text('deskripsi'); // Deskripsi detail pemasukan
            $table->decimal('jumlah', 15, 2); // Jumlah pemasukan
            $table->date('tanggal_pemasukan'); // Tanggal pemasukan
            $table->enum('kategori', ['kas_anggota', 'donasi', 'sponsor', 'penjualan', 'hibah', 'lainnya'])->default('lainnya'); // Kategori pemasukan
            $table->enum('metode_pembayaran', ['tunai', 'transfer_bank', 'e_wallet', 'cek', 'lainnya'])->default('tunai'); // Metode pembayaran
            $table->string('nomor_referensi')->nullable(); // Nomor referensi (nomor transfer, nomor cek, dll)
            $table->foreignId('kas_anggota_id')->nullable()->constrained('kas_anggota')->onDelete('set null'); // Relasi ke kas anggota jika pemasukan dari kas
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // User yang terkait dengan pemasukan (jika dari kas anggota)
            $table->string('bukti_pemasukan')->nullable(); // Path file bukti pemasukan (foto/scan)
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending'); // Status verifikasi
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // User yang membuat record (bendahara)
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); // User yang memverifikasi
            $table->timestamp('verified_at')->nullable(); // Waktu verifikasi
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
     * 
     * Menghapus tabel pemasukan
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
    }
};