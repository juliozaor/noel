<?php

namespace Database\Factories;

use App\Models\Members;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MembersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Members::class;

    public function definition(): array
    {
        return [
            'name'=> $this->faker->name(),
            'document'=> $this->faker->numberBetween($min = 100000, $max = 300000),
            'is_minor'=> true
        ];
        
    }
}
