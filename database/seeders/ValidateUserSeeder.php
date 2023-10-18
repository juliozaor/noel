<?php

namespace Database\Seeders;

use App\Models\ValidateUsers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ValidateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ValidateUsers::create([
            'id'=>1,
            'name' => 'Registrar todos los usuarios',
            'status' =>false
        ]);
    }
}
