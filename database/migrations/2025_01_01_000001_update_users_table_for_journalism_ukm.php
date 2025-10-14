<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom NIM dan mengupdate role system untuk UKM Jurnalistik
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom NIM (Nomor Induk Mahasiswa) jika belum ada
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim', 20)->nullable()->after('name');
            }
            
            // Mengubah enum role untuk menyesuaikan dengan struktur UKM Jurnalistik
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
        
        // Menambahkan kembali kolom role dengan enum yang baru
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', [
                    'koordinator_jurnalistik',
                    'sekretaris',
                    'bendahara',
                    'koordinator_redaksi',
                    'koordinator_litbang',
                    'koordinator_humas',
                    'koordinator_media_kreatif',
                    'anggota_redaksi',
                    'anggota_litbang',
                    'anggota_humas',
                    'anggota_media_kreatif'
                ])->default('anggota_redaksi')->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Mengembalikan struktur tabel users ke kondisi semula
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom NIM jika ada
            if (Schema::hasColumn('users', 'nim')) {
                $table->dropColumn('nim');
            }
            
            // Menghapus kolom role yang baru jika ada
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
        
        // Mengembalikan kolom role yang lama
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'member'])->default('member')->after('password');
            }
        });
    }
};