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
    Schema::table('posts', function (Blueprint $table) {
        //$table->longText('content')->nullable(); // Texto completo da notícia
        ///$table->string('imagecapa')->nullable();     // Foto de capa
        //$table->string('slugcapa')->unique()->after('title'); // URL amigável
        //$table->date('published_atcapa')->default(now());
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
};
