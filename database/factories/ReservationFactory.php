<?php

namespace Database\Factories;

use App\Models\Programming;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Reservation::class;
    public function definition(): array
    {
        return [
            'quota' =>$this->faker->numberBetween($min = 100, $max = 300),
            'confirmed' =>true,
            'reservation_date' =>$this->faker->dateTime($max = 'now', $timezone = null),
            'confirmation_date' =>$this->faker->dateTime($max = 'now', $timezone = null),
            'user_id' =>User::all()->random()->id ,
            'programming_id' => Programming::all()->random()->id

        ];
    }
}
