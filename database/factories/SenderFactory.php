<?php

namespace Database\Factories;

use App\Models\Sender;
use Illuminate\Database\Eloquent\Factories\Factory;

class SenderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sender::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email_address' => $this->faker->unique()->safeEmail,
            'auth_login_type' => $this->faker->boolean,
            'smtp_options' => [], // Add appropriate values for other fields
            'imap_options' => [],
            'auth_token' => [],
            'other_options' => [],
            'daily_limit' => $this->faker->numberBetween(0, 100),
            'daily_send_count' => 0,
        ];
    }
}
