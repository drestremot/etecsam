<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->tinyInteger('month')->nullable();
            $table->string('primary_color', 20)->default('#cc0000');
            $table->string('secondary_color', 20)->default('#ffffff');
            $table->string('accent_color', 20)->default('#ffcc00');
            $table->string('text_color', 20)->default('#333333');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_themes');
    }
};
