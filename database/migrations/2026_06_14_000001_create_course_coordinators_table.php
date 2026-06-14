<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('course_coordinators');
        Schema::create('course_coordinators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('tecnico'); // 'tecnico' | 'descentralizado'
            $table->unsignedTinyInteger('order')->default(0);
            $table->unique(['course_id', 'teacher_id', 'role']);
        });

        // technical_coordinator_id e decentralized_coordinator_id permanecem no
        // schema por limitação do SQLite (FK impede DROP COLUMN), mas são
        // ignoradas pelo ORM — a relação agora é feita via course_coordinators.
    }

    public function down(): void
    {
        Schema::dropIfExists('course_coordinators');

        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('technical_coordinator_id')->nullable();
            $table->unsignedBigInteger('decentralized_coordinator_id')->nullable();
        });
    }
};
