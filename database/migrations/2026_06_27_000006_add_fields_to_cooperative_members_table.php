<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cooperative_members', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('registration_number');
            $table->string('email')->nullable()->after('phone');
            $table->string('sex', 1)->nullable()->after('email');
            $table->string('guardian_name')->nullable()->after('sex');
            $table->string('guardian_phone')->nullable()->after('guardian_name');
            $table->string('photo')->nullable()->after('guardian_phone');
            $table->date('joined_at')->nullable()->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('cooperative_members', function (Blueprint $table) {
            $table->dropColumn(['phone', 'email', 'sex', 'guardian_name', 'guardian_phone', 'photo', 'joined_at']);
        });
    }
};
