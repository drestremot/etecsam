<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperative_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('period')->nullable();
            $table->string('file_path')->nullable();
            $table->string('url')->nullable();
            $table->date('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperative_reports');
    }
};
