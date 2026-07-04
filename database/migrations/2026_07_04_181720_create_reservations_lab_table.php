<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lab_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('space_id')->constrained()->cascadeOnDelete();
            $table->foreignId('auxiliar_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->text('description')->nullable();
            $table->text('obs')->nullable();
            $table->enum('status', [
                'pre_alocada',
                'aprovada',
                'em_execucao',
                'aguardando_conferencia',
                'conferida',
                'concluida',
                'finalizada',
                'recusada',
            ])->default('pre_alocada');
            $table->string('checklist_file')->nullable();
            $table->string('scanned_doc')->nullable();
            $table->string('delivery_photo')->nullable();
            $table->string('return_photo')->nullable();
            $table->timestamp('confirmed_by_auxiliar_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_reservations');
    }
};
