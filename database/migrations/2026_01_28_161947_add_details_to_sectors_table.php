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
    Schema::table('sectors', function (Blueprint $table) {
        $table->text('description')->nullable()->after('summary'); // Texto longo explicativo
        $table->json('images')->nullable()->after('icon'); // Lista de URLs das fotos (Carrossel)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sectors', function (Blueprint $table) {
            //
        });
    }
};
