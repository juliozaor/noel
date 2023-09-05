<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Profile::class;
    public function definition(): array
    {
        return [
            'document'=> $this->faker->numberBetween($min = 100000, $max = 300000),
            'cell'=> $this->faker->phoneNumber(),
            'address'=> $this->faker->streetAddress(),
            'neighborhood'=> $this->faker->streetName(),
            'birth'=> $this->faker->dateTime($max = 'now', $timezone = null),
            'eps'=> $this->faker->word(10),
            'reference'=>$this->faker->numberBetween($min = 1, $max = 5),
            'experience2022'=> true,
            'user_id'=> User::all()->random()->id
        ];
    }
}
