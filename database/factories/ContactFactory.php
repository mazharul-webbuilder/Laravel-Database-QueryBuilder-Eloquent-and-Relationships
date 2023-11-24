<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => DB::table('users')->inRandomOrder()->value('id'),
            'address' => fake()->unique()->address,
            'number' => fake()->phoneNumber,
            'city' => fake()->city,
            'zip_code' => fake()->unique()->postcode
        ];
    }
}
