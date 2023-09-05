<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'id'=>1,
            'name' => 'Vive la magia de la navidad',
            'detail' => '',
            'description' => '',
        ]);
        
        

    }
}
