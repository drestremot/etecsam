<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_clock_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('work_schedule_id')->nullable()->constrained('work_schedules')->nullOnDelete();
            $table->timestamp('recorded_at')->useCurrent()->index();
            $table->string('record_type', 30); // entry_1, exit_1, entry_2, exit_2, extra_entry, extra_exit
            $table->string('verification_method', 40)->default('facial_recognition'); // facial_recognition, gps_geolocation, totem_kiosk, manual
            $table->string('photo_snapshot')->nullable(); // caminho da foto capturada
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedSmallInteger('accuracy_meters')->nullable();
            $table->unsignedInteger('distance_to_unit_meters')->nullable();
            $table->boolean('is_within_geofence')->default(false);
            $table->boolean('is_within_schedule')->default(true);
            $table->integer('delay_minutes')->default(0);
            $table->string('status', 30)->default('approved'); // approved, flagged_outside_unit, flagged_late, flagged_extra, justified
            $table->text('justification')->nullable();
            $table->foreignId('justified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('justified_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'recorded_at']);
            $table->index(['unit_id', 'recorded_at']);
            $table->index(['status', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_clock_records');
    }
};

