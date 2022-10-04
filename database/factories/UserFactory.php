<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
#hash
use Illuminate\Support\Facades\Hash;
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
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // password
            'remember_token' => Str::random(10),
            'branch_id' => fake()->numberBetween(1, 2),
            'role' => fake()->randomElement(['admin', 'ro', 'branch_manager', 'user']),
            //role_id is the foreign key for the roles table
            'role_id' => fake()->numberBetween(3, 6),
            'national_id' => fake()->ssn(),
            'status' => fake()->randomElement(['active', 'inactive']),
            //email verified set as no
            'email_verified' =>  fake()->randomElement(['yes', 'no']),
            //fake phone number in the format 2547xxxxxxxx or 07xxxxxxxx or 011`
            'phone' => fake()-> e164PhoneNumber(),

          
           






        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
