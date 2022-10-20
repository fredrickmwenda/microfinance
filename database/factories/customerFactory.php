<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class customerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [ 
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'national_id' => fake()->ssn(),
            'address' => fake()->address(),
            'created_by' => fake()->numberBetween(1, 3),
            // 'state' => fake()->state(),
            // 'zip' => fake()->postcode(),
            // 'country' => fake()->country(),
            // 'notes' => fake()->text(),
            'branch_id' => fake()->numberBetween(1, 10),
            'guarantor_first_name' => fake()->firstName(),
            'guarantor_last_name' => fake()->lastName(),
            'guarantor_email' => fake()->safeEmail(),
            'guarantor_phone' => fake()->phoneNumber(),
            'guarantor_national_id' => fake()->ssn(),
            'guarantor_address' => fake()->address(),
            'referee_first_name' => fake()->firstName(),
            'referee_last_name' => fake()->lastName(),
            'referee_phone' => fake()->phoneNumber(),
            'referee_relationship' => fake()->randomElement(['friend', 'family', 'coworker']),
            'next_of_kin_first_name' => fake()->firstName(),
            'next_of_kin_last_name' => fake()->lastName(),
            'next_of_kin_phone' => fake()->phoneNumber(),
            'next_of_kin_relationship' => fake()->randomElement(['friend', 'family', 'coworker']),
        ];
    }
}
