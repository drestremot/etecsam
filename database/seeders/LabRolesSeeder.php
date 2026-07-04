<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class LabRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Cria os 3 papéis
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'Professor']);
        Role::firstOrCreate(['name' => 'Auxiliar']);

        // Atribui 'admin' a TODOS os usuários com is_admin=true
        $admins = User::where('is_admin', true)->get();

        if ($admins->isEmpty()) {
            // Fallback: garante pelo menos o primeiro usuário
            $fallback = User::first();
            if ($fallback) {
                $fallback->syncRoles($adminRole);
                $this->command->info("Papel 'admin' atribuído (fallback) a: {$fallback->email}");
            }
            return;
        }

        foreach ($admins as $user) {
            $user->syncRoles($adminRole);
            $this->command->info("Papel 'admin' atribuído a: {$user->email}");
        }
    }
}
