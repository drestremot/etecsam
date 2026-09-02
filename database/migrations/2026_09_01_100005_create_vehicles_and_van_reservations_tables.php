<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Ex: "Van Escolar Oficial - Renault Master"
                $table->string('plate', 20)->unique(); // Ex: "BRA2E19"
                $table->string('brand', 50)->nullable(); // Ex: "Renault"
                $table->string('model', 50)->nullable(); // Ex: "Master Minibus"
                $table->integer('year')->nullable(); // Ex: 2024
                $table->integer('capacity')->default(16); // Capacidade de passageiros
                $table->unsignedBigInteger('current_km')->default(0); // Hodômetro atual
                $table->enum('status', ['disponivel', 'em_viagem', 'manutencao', 'inativo'])->default('disponivel');
                $table->string('fuel_type', 30)->default('Diesel');
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Inserir a Van Escolar padrão da instituição
            DB::table('vehicles')->insert([
                'name' => 'Van Escolar Etec - Renault Master Minibus',
                'plate' => 'ETC-2026',
                'brand' => 'Renault',
                'model' => 'Master Minibus Executiva',
                'year' => 2024,
                'capacity' => 16,
                'current_km' => 45280,
                'status' => 'disponivel',
                'fuel_type' => 'Diesel S10',
                'notes' => 'Veículo oficial para visitas técnicas, eventos acadêmicos, feiras tecnológicas e transporte institucional.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!Schema::hasTable('van_reservations')) {
            Schema::create('van_reservations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Solicitante
                $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete(); // Veículo
                $table->string('purpose'); // Motivo / Finalidade
                $table->string('destination'); // Destino / Itinerário

                // Datas e Horários
                $table->date('departure_date');
                $table->time('departure_time');
                $table->date('return_date');
                $table->time('return_time');

                // Passageiros e Condutor
                $table->integer('passengers_count')->default(1);
                $table->text('passenger_list')->nullable();
                $table->string('driver_type', 40)->default('servidor_habilitado');
                $table->string('driver_name');
                $table->string('driver_cnh', 30)->nullable();
                $table->string('driver_phone', 30)->nullable();

                // Regra das 72 horas
                $table->boolean('is_within_72h_deadline')->default(true);
                $table->integer('hours_in_advance')->default(72);

                // Status do fluxo
                $table->enum('status', [
                    'pendente',       // Aguardando liberação da Diretora de Serviços
                    'aprovada',       // Liberada pela Diretora de Serviços
                    'rejeitada',      // Recusada pela Diretoria
                    'em_andamento',   // Em viagem (KM inicial informada)
                    'concluida',      // Viagem finalizada (KM final informada)
                    'cancelada'       // Cancelada
                ])->default('pendente');

                // Controle de Hodômetro / Quilometragem
                $table->unsignedBigInteger('initial_km')->nullable();
                $table->unsignedBigInteger('final_km')->nullable();
                $table->unsignedBigInteger('total_km')->nullable();
                $table->string('initial_km_photo')->nullable();
                $table->string('final_km_photo')->nullable();

                // Nível de Combustível e Condições do Veículo
                $table->string('fuel_level_departure', 20)->nullable();
                $table->string('fuel_level_return', 20)->nullable();
                $table->text('checklist_notes')->nullable();

                // Aprovação e Liberação
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->dateTime('approved_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->text('director_notes')->nullable();

                // Conclusão
                $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->dateTime('completed_at')->nullable();

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('van_reservation_audits')) {
            Schema::create('van_reservation_audits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('van_reservation_id')->constrained('van_reservations')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action'); // 'criada', 'aprovada', 'rejeitada', 'viagem_iniciada', 'viagem_concluida', 'cancelada'
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
        Schema::dropIfExists('van_reservation_audits');
        Schema::dropIfExists('van_reservations');
        Schema::dropIfExists('vehicles');
    }
};

