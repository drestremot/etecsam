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
            $table->text('coordenador_obs')->nullable()->after('auxiliar_obs');
        });
    }

    public function down(): void
    {
        Schema::table('lab_reservations', function (Blueprint $table) {
            $table->dropColumn('coordenador_obs');
        });
    }
};
