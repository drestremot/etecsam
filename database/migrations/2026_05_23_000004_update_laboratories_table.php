<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->text('description')->nullable()->change();
            $table->text('full_description')->nullable()->after('description');
            $table->string('location')->nullable()->after('full_description');
            $table->integer('capacity')->nullable()->after('location');
            $table->unsignedBigInteger('responsible_id')->nullable()->after('capacity');
            $table->foreign('responsible_id')->references('id')->on('teachers')->nullOnDelete();
            $table->unsignedBigInteger('unit_id')->nullable()->after('responsible_id');
            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('unit_id');
        });

        Schema::table('laboratories', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->foreign('course_id')->references('id')->on('courses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->dropColumn(['slug', 'full_description', 'location', 'capacity', 'responsible_id', 'unit_id', 'is_active']);
        });
    }
};
