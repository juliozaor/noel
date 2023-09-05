<?php

namespace Database\Seeders;

use App\Models\Programming;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgrammingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Programming::create([
            'id'=>1,
            'quota' => 0,
            'quota_available' => 0,
            'event_id' => 1,
            'waiting' => true
        ]);
        

    }
}
