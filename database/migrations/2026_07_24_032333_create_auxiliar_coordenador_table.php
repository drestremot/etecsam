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
        Schema::create('auxiliar_coordenador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auxiliar_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('coordenador_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['auxiliar_id', 'coordenador_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auxiliar_coordenador');
    }
};
