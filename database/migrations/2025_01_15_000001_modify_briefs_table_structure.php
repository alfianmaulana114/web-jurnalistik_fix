<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Mengubah struktur tabel briefs menjadi lebih sederhana:
     * - judul
     * - tanggal
     * - isi_brief
     * - link_referensi
     */
    public function up(): void
    {
        Schema::table('briefs', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['created_by']);
            $table->dropForeign(['assigned_to']);
            
            // Drop columns yang tidak diperlukan
            $table->dropColumn([
                'deskripsi',
                'angle_berita',
                'target_narasumber',
                'deadline',
                'prioritas',
                'status',
                'catatan',
                'created_by',
                'assigned_to'
            ]);
            
            // Add new columns
            $table->date('tanggal')->after('judul');
            $table->text('isi_brief')->after('tanggal');
            $table->longText('link_referensi')->nullable()->after('isi_brief');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Mengembalikan struktur tabel briefs ke bentuk semula
     */
    public function down(): void
    {
        Schema::table('briefs', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['tanggal', 'isi_brief', 'link_referensi']);
            
            // Add back original columns
            $table->text('deskripsi')->after('judul');
            $table->text('angle_berita')->after('deskripsi');
            $table->text('target_narasumber')->nullable()->after('angle_berita');
            $table->date('deadline')->after('target_narasumber');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'urgent'])->default('sedang')->after('deadline');
            $table->enum('status', ['draft', 'approved', 'assigned', 'in_progress', 'completed', 'rejected'])->default('draft')->after('prioritas');
            $table->text('catatan')->nullable()->after('status');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->after('catatan');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
        });
    }
};