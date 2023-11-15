<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $password;

        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => $password ?: $password = bcrypt('secret'),
            'remember_token' => Str::random(10),
            'workdays_per_week' => 5,
            'seniority' => 0,
            'is_active' => 1,
            'branch_id' => 1
        ];
    }
}
