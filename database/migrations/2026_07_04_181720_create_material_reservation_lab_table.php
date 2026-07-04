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
        Schema::create('lab_material_reservation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_reservation_id')->constrained('lab_reservations')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity_requested')->default(1);
            $table->integer('quantity_used')->nullable();
            $table->boolean('delivered')->default(false);
            $table->boolean('returned')->default(false);
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_material_reservation');
    }
};
