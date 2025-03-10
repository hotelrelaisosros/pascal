<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\TalkType;
class TalkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->title(),
            'type' => $this->faker->randomElement(TalkType::all()),
            'length' =>rand(15,60),
            'abstract' =>  $this->faker->paragraph(),
            'organizer_notes' => $this->faker->paragraph(),
        ];
    }
}
