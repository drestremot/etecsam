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
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Ex: Técnico em Agropecuária
        $table->string('slug')->unique();
        $table->string('type'); // Ex: Integrado ao Médio, Modular Noturno
        $table->text('description'); // Resumo curto
        $table->longText('content')->nullable(); // Conteúdo completo (grade, perfil)
        $table->string('image')->nullable(); // Foto do curso
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        $table->string('photo')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
