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
        $rele1 = Role::create(['name'=>'Admin']);
        $rele2 = Role::create(['name'=>'user']);

        Permission::create(['name' => 'edit articles']);//->syncRoles([$rele1,$rele2])

    }
}
