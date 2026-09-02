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
        if (!Schema::hasTable('legal_leaves')) {
            Schema::create('legal_leaves', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('type'); // eleicao, juri_popular, doacao_sangue, alistamento, casamento, luto, convocacao_judicial, outro
                $table->string('description');
                $table->string('document_number')->nullable();
                $table->date('event_date')->nullable();
                $table->integer('days_granted')->default(1);
                $table->integer('days_used')->default(0);
                $table->integer('days_remaining')->default(1);
                $table->date('expiration_date')->nullable();
                $table->string('attachment_path');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status')->default('ativo'); // ativo, esgotado, expirado
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('legal_leave_requests')) {
            Schema::create('legal_leave_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('legal_leave_id')->constrained('legal_leaves')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->date('requested_date');
                $table->text('reason')->nullable();
                $table->string('status')->default('pendente'); // pendente, homologada, recusada, cancelada
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('legal_leave_audits')) {
            Schema::create('legal_leave_audits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('legal_leave_id')->constrained('legal_leaves')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action'); // 'criada', 'solicitacao_criada', 'homologada', 'recusada'
                $table->integer('days_impact')->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_leave_audits');
        Schema::dropIfExists('legal_leave_requests');
        Schema::dropIfExists('legal_leaves');
    }
};

