<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'whatsapp_number' => fake()->phoneNumber(),
            'birthday' => fake()->optional(0.8)->dateTimeBetween('-60 years', '-18 years'),
            'anniversary_date' => fake()->optional(0.6)->dateTimeBetween('-20 years', 'now'),
            'premium_due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+1 year'),
            'policy_name' => fake()->optional(0.8)->randomElement(['Health Shield Gold', 'Life Secure Plus', 'Auto Protect Max', 'Home Cover Pro']),
            'policy_number' => fake()->optional(0.8)->bothify('POL-####-????'),
            'company_name' => fake()->optional(0.4)->company(),
            'address' => fake()->optional(0.7)->address(),
            'notes' => fake()->optional(0.5)->paragraph(),
            'status' => fake()->randomElement(['active', 'active', 'active', 'inactive']),
        ];
    }
}
