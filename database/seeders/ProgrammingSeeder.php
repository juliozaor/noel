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
            'quota' => 999999999,
            'quota_available' => 999999999,
            'initial_date'=> '2025-12-01',
            'initial_time' => '10:00:00',
            'event_id' => 1,
            'waiting' => true
        ]);
        

    }
}
