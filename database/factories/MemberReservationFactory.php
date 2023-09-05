<?php

namespace Database\Factories;

use App\Models\Members;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberReservation>
 */
class MemberReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id'=>Members::all()->random()->id,
            'reservation_id'=>Reservation::all()->random()->id
        ];
    }
}
