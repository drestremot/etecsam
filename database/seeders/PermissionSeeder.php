<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista de todas as permissões do sistema agrupadas por módulo
        $permissions = [
            // Demandas (KanbanTec)
            ['name' => 'tasks.view',        'guard_name' => 'web'],
            ['name' => 'tasks.create',      'guard_name' => 'web'],
            ['name' => 'tasks.edit',        'guard_name' => 'web'],
            ['name' => 'tasks.delete',      'guard_name' => 'web'],
            ['name' => 'tasks.report',      'guard_name' => 'web'],

            // Reservas de Laboratórios
            ['name' => 'lab.reservations.view',     'guard_name' => 'web'],
            ['name' => 'lab.reservations.create',   'guard_name' => 'web'],
            ['name' => 'lab.reservations.approve',  'guard_name' => 'web'],
            ['name' => 'lab.reservations.finalize', 'guard_name' => 'web'],

            // Atestados Médicos
            ['name' => 'medical.view',      'guard_name' => 'web'],
            ['name' => 'medical.create',    'guard_name' => 'web'],
            ['name' => 'medical.manage',    'guard_name' => 'web'],

            // Folgas Previstas em Lei
            ['name' => 'leaves.view',       'guard_name' => 'web'],
            ['name' => 'leaves.request',    'guard_name' => 'web'],
            ['name' => 'leaves.manage',     'guard_name' => 'web'],

            // Van Escolar
            ['name' => 'van.view',          'guard_name' => 'web'],
            ['name' => 'van.create',        'guard_name' => 'web'],
            ['name' => 'van.approve',       'guard_name' => 'web'],
            ['name' => 'van.manage',        'guard_name' => 'web'],

            // Cursos & Grade
            ['name' => 'courses.view',      'guard_name' => 'web'],
            ['name' => 'courses.manage',    'guard_name' => 'web'],

            // Espaços Didáticos & Inventário
            ['name' => 'spaces.manage',     'guard_name' => 'web'],
            ['name' => 'materials.manage',  'guard_name' => 'web'],

            // Usuários & Governança
            ['name' => 'users.manage',       'guard_name' => 'web'],
            ['name' => 'permissions.manage', 'guard_name' => 'web'],

            // Ponto Eletrônico & Jornada
            ['name' => 'timeclock.punch',    'guard_name' => 'web'],
            ['name' => 'timeclock.view_own', 'guard_name' => 'web'],
            ['name' => 'timeclock.manage',   'guard_name' => 'web'],
            ['name' => 'schedules.manage',   'guard_name' => 'web'],

            // Auditoria
            ['name' => 'audit.view',        'guard_name' => 'web'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p['name'], 'guard_name' => $p['guard_name']]);
        }

        // Criar/Garantir Papéis Básicos
        $roles = [
            'Superintendente',
            'Diretor',
            'Diretora de Serviços',
            'Coordenador',
            'Professor',
            'Auxiliar',
            'admin',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $allPermissions = Permission::all();

        // 1. Administrador & Superintendente & Diretor -> Todas as permissões
        Role::findByName('admin')->syncPermissions($allPermissions);
        Role::findByName('Superintendente')->syncPermissions($allPermissions);
        Role::findByName('Diretor')->syncPermissions($allPermissions);
        Role::findByName('Diretora de Serviços')->syncPermissions($allPermissions);

        // 2. Coordenador
        $coordenadorPermissions = Permission::whereIn('name', [
            'tasks.view', 'tasks.create', 'tasks.edit', 'tasks.report',
            'lab.reservations.view', 'lab.reservations.create', 'lab.reservations.approve', 'lab.reservations.finalize',
            'medical.view', 'medical.create',
            'leaves.view', 'leaves.request',
            'van.view', 'van.create', 'van.approve',
            'courses.view', 'courses.manage',
            'spaces.manage', 'materials.manage',
            'timeclock.punch', 'timeclock.view_own', 'timeclock.manage', 'schedules.manage',
        ])->get();
        Role::findByName('Coordenador')->syncPermissions($coordenadorPermissions);

        // 3. Professor
        $professorPermissions = Permission::whereIn('name', [
            'tasks.view', 'tasks.create',
            'lab.reservations.view', 'lab.reservations.create',
            'medical.view', 'medical.create',
            'leaves.view', 'leaves.request',
            'van.view', 'van.create',
            'courses.view',
            'timeclock.punch', 'timeclock.view_own',
        ])->get();
        Role::findByName('Professor')->syncPermissions($professorPermissions);

        // 4. Auxiliar
        $auxiliarPermissions = Permission::whereIn('name', [
            'tasks.view', 'tasks.create',
            'lab.reservations.view', 'lab.reservations.finalize',
            'materials.manage', 'spaces.manage',
            'medical.view', 'medical.create',
            'leaves.view', 'leaves.request',
            'timeclock.punch', 'timeclock.view_own',
        ])->get();
        Role::findByName('Auxiliar')->syncPermissions($auxiliarPermissions);
    }
}

