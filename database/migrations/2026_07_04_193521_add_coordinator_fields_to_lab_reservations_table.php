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
        Schema::table('lab_reservations', function (Blueprint $table) {
            $table->foreignId('coordenador_id')->nullable()->constrained('users')->nullOnDelete()->after('auxiliar_id');
            $table->timestamp('professor_released_at')->nullable()->after('confirmed_by_auxiliar_at');
            $table->timestamp('auxiliar_released_at')->nullable()->after('professor_released_at');
            $table->timestamp('validated_at')->nullable()->after('auxiliar_released_at');
            $table->timestamp('professor_signed_at')->nullable()->after('validated_at');
        });
    }

    public function down(): void
    {
        Schema::table('lab_reservations', function (Blueprint $table) {
            $table->dropForeign(['coordenador_id']);
            $table->dropColumn(['coordenador_id','professor_released_at','auxiliar_released_at','validated_at','professor_signed_at']);
        });
    }
};
