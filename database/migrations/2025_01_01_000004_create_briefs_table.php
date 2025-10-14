<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel briefs untuk menyimpan brief berita dari divisi litbang
     */
    public function up(): void
    {
        if (!Schema::hasTable('briefs')) {
            Schema::create('briefs', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi');
                $table->text('angle_berita'); // Sudut pandang berita yang diinginkan
                $table->text('target_narasumber')->nullable(); // Target narasumber yang akan diwawancara
                $table->date('deadline');
                $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'urgent'])->default('sedang');
                $table->enum('status', ['draft', 'approved', 'assigned', 'in_progress', 'completed', 'rejected'])->default('draft');
                $table->text('catatan')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // User dari divisi litbang
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // User yang ditugaskan
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Menghapus tabel briefs
     */
    public function down(): void
    {
        Schema::dropIfExists('briefs');
    }
};