<?php

namespace Database\Factories;

use App\Models\ConstraintType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConstraintTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConstraintType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'code' => $this->faker->firstName,
            'is_work' => $this->faker->boolean(30),
            'is_single_day' => $this->faker->boolean(50),
            'is_group_constraint' => 0,
            'is_day_in_schedule' => 0,
            'branch_id' => 1
        ];
    }
}
