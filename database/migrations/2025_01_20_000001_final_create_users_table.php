<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration final untuk tabel users dengan semua kolom yang diperlukan
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim', 20)->nullable();
            $table->string('email')->unique();
            $table->string('password');
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
            ])->default('anggota_redaksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

