<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('department_user')) {
            Schema::create('department_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('department_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'department_id']);
            });
        }

        if (!Schema::hasTable('course_user')) {
            Schema::create('course_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('course_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'course_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_user');
        Schema::dropIfExists('department_user');
    }
};

