<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel notulensi rapat
        Schema::create('notulensi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->text('isi_notulensi');
            $table->string('tempat')->nullable();
            $table->text('peserta')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        // Tabel absen
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('status'); // hadir, izin, sakit, tidak_hadir
            $table->text('keterangan')->nullable();
            $table->foreignId('notulensi_id')->nullable()->constrained('notulensi')->onDelete('cascade');
            $table->string('bulan'); // januari, februari, etc
            $table->integer('tahun');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'tanggal']);
            $table->index(['bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absen');
        Schema::dropIfExists('notulensi');
    }
};

