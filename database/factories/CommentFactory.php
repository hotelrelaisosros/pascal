<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => User::factory(),
            "blog_id" => Blog::factory(),
            "title" => $this->faker->title(),
            "message" => $this->faker->paragraph(),
            'status' =>  $this->faker->boolean(),
        ];
    }
}
