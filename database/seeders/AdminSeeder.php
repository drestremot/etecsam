<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@etecsam.com.br'],
            [
                'name'           => 'Administrador',
                'email'          => 'admin@etecsam.com.br',
                'password'       => Hash::make('Admin@2025'),
                'is_admin'       => 1,
                'email_verified_at' => now(),
                'updated_at'     => now(),
            ]
        );
    }
}
