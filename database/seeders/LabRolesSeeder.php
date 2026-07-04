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
        $admin     = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'Professor']);
        Role::firstOrCreate(['name' => 'Auxiliar']);

        // Atribui admin ao usuário existente (primeiro admin ou admin@etecsam)
        $adminUser = User::where('is_admin', true)->first()
            ?? User::where('email', 'admin@etecsam.sp.gov.br')->first()
            ?? User::first();

        if ($adminUser) {
            $adminUser->syncRoles($admin);
            $this->command->info("Papel 'admin' atribuído a: {$adminUser->email}");
        }
    }
}
