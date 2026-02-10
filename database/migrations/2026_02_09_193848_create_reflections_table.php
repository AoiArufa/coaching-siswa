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

            $table->foreignId('coaching_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('hasil_perkembangan');
            $table->text('kendala');
            $table->text('evaluasi_metode');
            $table->text('catatan_guru')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reflections');
    }
};
