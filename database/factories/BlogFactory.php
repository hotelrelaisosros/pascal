<?php

namespace Database\Factories;

use App\Enums\BikeType;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
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
            'category_id' => Category::factory(),
            'title' => $this->faker->title(),
            'description' =>  $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(),
            'additional_images' => ([$this->faker->imageUrl(), $this->faker->imageUrl(), $this->faker->imageUrl()]),
        ];
    }
}
