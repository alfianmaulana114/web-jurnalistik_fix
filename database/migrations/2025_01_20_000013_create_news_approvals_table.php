<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('approved_at');
            $table->string('note')->nullable();
            $table->timestamps();
            $table->unique('news_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_approvals');
    }
};