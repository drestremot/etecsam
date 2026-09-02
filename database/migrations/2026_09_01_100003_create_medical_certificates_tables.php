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
        if (!Schema::hasTable('medical_certificates')) {
            Schema::create('medical_certificates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('type')->default('medico');
                $table->string('doctor_name')->nullable();
                $table->string('crm')->nullable();
                $table->string('cid')->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('days')->default(1);
                $table->text('description')->nullable();
                $table->string('attachment_path');
                $table->string('status')->default('pendente');
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('medical_certificate_audits')) {
            Schema::create('medical_certificate_audits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('medical_certificate_id')->constrained('medical_certificates')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action'); // 'criado', 'homologado', 'recusado', 'atualizado'
                $table->string('from_status')->nullable();
                $table->string('to_status')->nullable();
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
        Schema::dropIfExists('medical_certificate_audits');
        Schema::dropIfExists('medical_certificates');
    }
};

