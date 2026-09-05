<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_schedules', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('unit_id')->constrained('courses')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->after('course_id')->constrained('subjects')->nullOnDelete();
            $table->string('course_name', 150)->nullable()->after('shift_name');
            $table->string('division', 20)->nullable()->after('class_name');

            $table->index(['course_id', 'day_of_week']);
            $table->index(['subject_id']);
        });
    }

    public function down(): void
    {
        Schema::table('work_schedules', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['subject_id']);
            $table->dropIndex(['course_id', 'day_of_week']);
            $table->dropIndex(['subject_id']);
            $table->dropColumn(['course_id', 'subject_id', 'course_name', 'division']);
        });
    }
};
