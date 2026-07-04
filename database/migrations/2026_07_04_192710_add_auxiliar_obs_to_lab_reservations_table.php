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
            $table->text('auxiliar_obs')->nullable()->after('obs');
        });
    }

    public function down(): void
    {
        Schema::table('lab_reservations', function (Blueprint $table) {
            $table->dropColumn('auxiliar_obs');
        });
    }
};
