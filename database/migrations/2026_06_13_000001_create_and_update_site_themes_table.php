<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('site_themes')) {
            Schema::create('site_themes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->tinyInteger('month')->nullable();
                $table->string('primary_color', 20)->default('#1a3a6e');
                $table->string('secondary_color', 20)->default('#2563eb');
                $table->string('accent_color', 20)->default('#f5a623');
                $table->string('text_color', 20)->default('#ffffff');
                $table->text('description')->nullable();
                $table->string('popup_image')->nullable();
                $table->boolean('active')->default(false);
                $table->timestamps();
            });
        } else {
            Schema::table('site_themes', function (Blueprint $table) {
                if (!Schema::hasColumn('site_themes', 'description')) {
                    $table->text('description')->nullable()->after('text_color');
                }
                if (!Schema::hasColumn('site_themes', 'popup_image')) {
                    $table->string('popup_image')->nullable()->after('description');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_themes');
    }
};
