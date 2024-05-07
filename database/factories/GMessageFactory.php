<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GMessage;
use App\Models\Sender;
use Illuminate\Support\Arr;

class GMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph(28),
            'sender_id' => Sender::factory(),
            'header' => [], // Assuming you want an empty array as default
            'labels' => 'inbox,unread', // Default labels
            'reminder' => 0, // Default reminder value
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now')
        ];
    }
}
