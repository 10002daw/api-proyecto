<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->realTextBetween(3, rand(10, 30)),
            'description'  => $this->faker->realTextBetween(5, rand(10, 100)),
            'private' => $this->faker->numberBetween(0,1),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password'
        ];
    }
}
