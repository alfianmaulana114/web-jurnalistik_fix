<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom jabatan_panitia dan tugas_khusus yang hilang pada tabel proker_panitias
     */
    public function up(): void
    {
        Schema::table('proker_panitias', function (Blueprint $table) {
            // Menambahkan kolom jabatan_panitia jika belum ada
            if (!Schema::hasColumn('proker_panitias', 'jabatan_panitia')) {
                $table->string('jabatan_panitia')->after('user_id'); // Ketua, Wakil Ketua, Sekretaris, Bendahara, Anggota, dll
            }
            
            // Menambahkan kolom tugas_khusus jika belum ada
            if (!Schema::hasColumn('proker_panitias', 'tugas_khusus')) {
                $table->text('tugas_khusus')->nullable()->after('jabatan_panitia'); // Tugas khusus dalam proker
            }
        });
        
        // Menambahkan unique constraint jika belum ada
        try {
            Schema::table('proker_panitias', function (Blueprint $table) {
                $table->unique(['proker_id', 'user_id', 'jabatan_panitia'], 'proker_panitias_unique');
            });
        } catch (Exception $e) {
            // Constraint mungkin sudah ada, abaikan error
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Menghapus kolom yang ditambahkan
     */
    public function down(): void
    {
        Schema::table('proker_panitias', function (Blueprint $table) {
            // Menghapus unique constraint jika ada
            try {
                $table->dropUnique('proker_panitias_unique');
            } catch (Exception $e) {
                // Constraint mungkin tidak ada, abaikan error
            }
            
            // Menghapus kolom tugas_khusus jika ada
            if (Schema::hasColumn('proker_panitias', 'tugas_khusus')) {
                $table->dropColumn('tugas_khusus');
            }
            
            // Menghapus kolom jabatan_panitia jika ada
            if (Schema::hasColumn('proker_panitias', 'jabatan_panitia')) {
                $table->dropColumn('jabatan_panitia');
            }
        });
    }
};