<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reflections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coaching_id')->constrained()->cascadeOnDelete();
            $table->text('reflection');
            $table->text('hasil_perkembangan')->nullable();
            $table->text('kendala')->nullable();
            $table->text('rencana_perbaikan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reflections');
    }
};
