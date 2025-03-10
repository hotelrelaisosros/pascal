<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $startsat = now()->addMonths(6);
        $endsAt = $startsat->clone()->addDays(3);
        $cfpStartsAt = $startsat->clone()->subMonths(4);
        $cfpEndsAt = $startsat->clone()->addMonths(2);
        return [
            'title'=> $this->faker->sentence(),
            'location'=>$this->faker->city() . ', ' . $this->faker->country(),
            'description' => $this->faker->paragraph(),
            'url' => $this->faker->url(),
            'starts_at'=>$startsat,
            'ends_at'=>$endsAt,
            'cfp_starts_at'=> $cfpStartsAt,
            'cfp_ends_at'=> $cfpEndsAt,
            ]
        ;
    }
}
