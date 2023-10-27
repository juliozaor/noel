<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name'=>'Admin']);
        $role2 = Role::create(['name'=>'User']);
        $role3 = Role::create(['name'=>'Super']);

        Permission::create(['name' => 'api'])->syncRoles([$role1,$role2,$role3 ]);
        Permission::create(['name' => 'administrador'])->syncRoles([$role1,$role3]);
        Permission::create(['name' => 'superAministrador'])->syncRoles([$role3]);
        

    }
}
