<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->text(50),
            'detail' => $this->faker->text(100),
            'description' => $this->faker->text(200),
            'image' => 'events/'.$this->faker->image('public/storage/events',640,480,null,false)
        ];
    }
}
