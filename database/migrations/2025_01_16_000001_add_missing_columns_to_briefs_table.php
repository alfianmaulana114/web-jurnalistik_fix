<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom yang hilang ke tabel briefs
     */
    public function up(): void
    {
        Schema::table('briefs', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('briefs', 'tanggal')) {
                $table->date('tanggal')->after('judul')->nullable();
            }
            
            if (!Schema::hasColumn('briefs', 'isi_brief')) {
                $table->text('isi_brief')->after('tanggal')->nullable();
            }
            
            if (!Schema::hasColumn('briefs', 'link_referensi')) {
                $table->longText('link_referensi')->nullable()->after('isi_brief');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('briefs', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'isi_brief', 'link_referensi']);
        });
    }
};