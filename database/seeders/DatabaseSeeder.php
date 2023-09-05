<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Event;
use App\Models\MemberReservation;
use App\Models\Members;
use App\Models\Profile;
use App\Models\Programming;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Storage::makeDirectory('events');

        $this->call(UserSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(ProgrammingSeeder::class);

      //  Profile::factory(4)->create();
       // Event::factory(4)->create();
        //Programming::factory(12)->create();
       // Members::factory(150)->create();
        //$this->call(ReservationSeeder::class);
        //  MemberReservation::factory(200);
        //  Reservation::factory(60)->create();

    }
}
