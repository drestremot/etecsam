<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 1 = Segunda, 2 = Terça, 3 = Quarta, 4 = Quinta, 5 = Sexta, 6 = Sábado, 0 = Domingo
            $table->string('shift_name', 100)->nullable(); // Ex: "Manhã - Técnico em Informática", "Noite - Administração"
            $table->time('start_time'); // Ex: 07:10:00
            $table->time('end_time');   // Ex: 12:35:00
            $table->time('break_start_time')->nullable(); // Ex: 09:40:00
            $table->time('break_end_time')->nullable();   // Ex: 10:00:00
            $table->unsignedSmallInteger('tolerance_minutes')->default(15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'day_of_week', 'is_active']);
            $table->index(['unit_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};

