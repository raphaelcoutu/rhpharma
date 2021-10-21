<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'code' => $this->faker->countryCode,
            'description' => '...',
            'branch_id' => '1',
            'workplace_id' => '1',
            'bonus_weeks' => 2,
            'bonus_pts' => 4,
            'malus_weeks' => 3,
            'malus_pts' => 8,
            'monday_am' => 2,
            'monday_pm' => 2,
            'tuesday_am' => 2,
            'tuesday_pm' => 2,
            'wednesday_am' => 2,
            'wednesday_pm' => 2,
            'thursday_am' => 2,
            'thursday_pm' => 2,
            'friday_am' => 2,
            'friday_pm' => 2
        ];
    }
}
