<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_schedules', function (Blueprint $table) {
            $table->string('subject_name', 150)->nullable()->after('shift_name'); // Ex: "Matemática (A)", "Matemática (B)"
            $table->string('class_name', 100)->nullable()->after('subject_name'); // Ex: "1º MTEC Informática - Turma A", "2º Adm"
            $table->string('classroom', 80)->nullable()->after('class_name');     // Ex: "Sala 01", "Lab Informática 1"
            $table->string('schedule_type', 30)->default('class')->after('classroom'); // class, coordination, administrative

            $table->index(['unit_id', 'day_of_week', 'start_time']);
            $table->index(['schedule_type']);
        });
    }

    public function down(): void
    {
        Schema::table('work_schedules', function (Blueprint $table) {
            $table->dropIndex(['unit_id', 'day_of_week', 'start_time']);
            $table->dropIndex(['schedule_type']);
            $table->dropColumn(['subject_name', 'class_name', 'classroom', 'schedule_type']);
        });
    }
};
