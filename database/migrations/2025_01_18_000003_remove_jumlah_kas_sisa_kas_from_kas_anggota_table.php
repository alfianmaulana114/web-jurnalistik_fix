<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menghapus kolom jumlah_kas dan sisa_kas dari tabel kas_anggota
     */
    public function up(): void
    {
        Schema::table('kas_anggota', function (Blueprint $table) {
            $table->dropColumn(['jumlah_kas', 'sisa_kas']);
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Menambahkan kembali kolom jumlah_kas dan sisa_kas ke tabel kas_anggota
     */
    public function down(): void
    {
        Schema::table('kas_anggota', function (Blueprint $table) {
            $table->decimal('jumlah_kas', 15, 2)->after('user_id'); // Jumlah kas yang harus dibayar
            $table->decimal('sisa_kas', 15, 2)->default(0)->after('jumlah_terbayar'); // Sisa kas yang belum dibayar
        });
    }
};