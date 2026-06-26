<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperative_sales', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('category')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('sale_date');
            $table->date('due_date');
            $table->date('received_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperative_sales');
    }
};
