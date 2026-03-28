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
    Schema::table('courses', function (Blueprint $table) {
        $table->foreignId('unit_id')->nullable()->constrained(); // Onde é o curso?
        $table->string('course_plan')->nullable(); // PDF do Plano de Curso
        $table->text('schedule')->nullable(); // Horários das aulas

        // Coordenador de Classe Descentralizada (pode ser null se for Sede)
        $table->unsignedBigInteger('decentralized_coordinator_id')->nullable();
        $table->foreign('decentralized_coordinator_id')->references('id')->on('teachers');

        // Coordenador Técnico do Curso
        $table->unsignedBigInteger('technical_coordinator_id')->nullable();
        $table->foreign('technical_coordinator_id')->references('id')->on('teachers');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
        });
    }
};
