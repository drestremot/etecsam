<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperative_housing_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_housing_tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cooperative_housing_fee_id')->constrained()->cascadeOnDelete();
            $table->boolean('paid')->default(false);
            $table->date('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['cooperative_housing_tenant_id', 'cooperative_housing_fee_id'], 'cooperative_housing_payments_tenant_fee_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperative_housing_payments');
    }
};
