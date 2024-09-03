<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
    public function definition(): array
    {
        return             
        [
            'nama' => $this->faker->name, 
            'email' => $this->faker->unique()->safeEmail,
            'kata_sandi' => Hash::make('password123'),
            'alamat' => $this->faker->address,
            'nomor_telepon' => $this->faker->phoneNumber,
            'tanggal_bergabung' => $this->faker->date,
            'peran' => 'user',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
