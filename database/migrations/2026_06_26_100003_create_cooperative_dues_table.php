<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperative_dues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cooperative_monthly_fee_id')->constrained()->cascadeOnDelete();
            $table->boolean('paid')->default(false);
            $table->date('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['cooperative_member_id', 'cooperative_monthly_fee_id'], 'cooperative_dues_member_fee_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperative_dues');
    }
};
