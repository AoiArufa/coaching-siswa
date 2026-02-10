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
        if (!Schema::hasTable('journals')) {
            Schema::create('journals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coaching_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->date('tanggal');
                $table->text('catatan');
                $table->text('refleksi')->nullable();
                $table->index('tanggal');               // Untuk filter by date, tambahan jika perlu
                $table->index('coaching_id');           // Untuk query relasi dengan coaching, tambahan jika perlu
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
