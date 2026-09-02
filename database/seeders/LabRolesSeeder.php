<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class LabRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Cria os papéis do sistema
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'Superintendente']);
        Role::firstOrCreate(['name' => 'Diretor']);
        Role::firstOrCreate(['name' => 'Coordenador']);
        Role::firstOrCreate(['name' => 'Professor']);
        Role::firstOrCreate(['name' => 'Auxiliar']);

        // Atribui 'admin' a usuários com is_admin=true que ainda não tenham outro papel
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $user) {
            if ($user->roles->isEmpty()) {
                $user->syncRoles($adminRole);
            }
        }
    }
}
