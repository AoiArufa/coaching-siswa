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
        Schema::create('coachings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('murid_id')->constrained('users')->cascadeOnDelete();
            $table->string('tujuan');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['draft', 'berjalan', 'selesai'])->default('draft');
            $table->timestamps();

            $table->index('guru_id');
            $table->index('murid_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coachings');
    }
};
