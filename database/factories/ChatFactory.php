<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'message' => $this->faker->paragraph(),
        ];
    }
}
