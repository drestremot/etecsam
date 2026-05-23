<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@etecsam.sp.gov.br'],
            [
                'name'              => 'Administrador Etec SAM',
                'password'          => Hash::make('Etec@2026!'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin criado: admin@etecsam.sp.gov.br / Etec@2026!');
    }
}
