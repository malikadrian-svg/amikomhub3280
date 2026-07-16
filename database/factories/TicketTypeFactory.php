<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketType>
 */
class TicketTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => 'Regular Ticket',
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(50, 500) * 1000,
            'quantity' => $this->faker->numberBetween(10, 100),
            'is_active' => true,
            'sort_order' => 1,
        ];
    }
}
