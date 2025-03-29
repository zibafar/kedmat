<?php

namespace App\Services\User\Database\Factories;

use App\Data\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '123456', // Hash::make('123456') Hash in model casting
            'remember_token' => null,
            'is_vip'=>false
        ];
    }

    /**
     * @return UserFactory
     */
    public function vip(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_vip' => true,
        ]);
    }


}
