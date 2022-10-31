<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'name' => $this->faker->name(10),
            // 'email' => $this->faker->unique()->email(20),
            // 'password' => $this->faker->password(20),
            'name' => 'Admin',
            'email' => 'admin@iti.com',
            'password' => bcrypt('admin')
        ];
    }
}
