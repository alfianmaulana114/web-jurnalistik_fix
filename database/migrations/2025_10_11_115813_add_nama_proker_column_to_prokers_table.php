<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prokers', function (Blueprint $table) {
            if (!Schema::hasColumn('prokers', 'nama_proker')) {
                $table->string('nama_proker')->after('id');
            }
            if (!Schema::hasColumn('prokers', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('nama_proker');
            }
            if (!Schema::hasColumn('prokers', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->after('deskripsi');
            }
            if (!Schema::hasColumn('prokers', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->after('tanggal_mulai');
            }
            if (!Schema::hasColumn('prokers', 'status')) {
                $table->enum('status', ['planning', 'ongoing', 'completed', 'cancelled'])->default('planning')->after('tanggal_selesai');
            }
            if (!Schema::hasColumn('prokers', 'catatan')) {
                $table->text('catatan')->nullable()->after('status');
            }
            if (!Schema::hasColumn('prokers', 'created_by')) {
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->after('catatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prokers', function (Blueprint $table) {
            if (Schema::hasColumn('prokers', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('prokers', 'catatan')) {
                $table->dropColumn('catatan');
            }
            if (Schema::hasColumn('prokers', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('prokers', 'tanggal_selesai')) {
                $table->dropColumn('tanggal_selesai');
            }
            if (Schema::hasColumn('prokers', 'tanggal_mulai')) {
                $table->dropColumn('tanggal_mulai');
            }
            if (Schema::hasColumn('prokers', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('prokers', 'nama_proker')) {
                $table->dropColumn('nama_proker');
            }
        });
    }
};
