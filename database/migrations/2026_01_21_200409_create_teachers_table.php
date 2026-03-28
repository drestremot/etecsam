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
    Schema::create('teachers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('role');     // Cargo
        $table->string('specialty')->nullable(); // Especialidade
        $table->string('email')->nullable();
        $table->string('photo')->nullable();
        $table->string('lattes_url')->nullable();

        // ADICIONE ESTAS DUAS LINHAS:
        $table->string('phone')->nullable();      // Telefone
        $table->date('birth_date')->nullable();   // Data de Nascimento

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
