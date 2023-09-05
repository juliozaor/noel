<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Programming;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Programming>
 */
class ProgrammingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Programming::class;

    public function definition(): array
    {
        $quota = $this->faker->numberBetween($min = 100, $max = 300);
        return [
            'quota' => $quota,
            'quota_available' => $quota,
            'initial_date' => $this->faker->dateTime($max = 'now', $timezone = null),
            'final_date' => $this->faker->dateTime($max = 'now', $timezone = null),
            'event_id' => Event::all()->random()->id
        ];
    }
}
