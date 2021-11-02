<?php

namespace Database\Factories;

use App\Models\Constraint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ConstraintFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Constraint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'start_datetime' => Carbon::parse('-2 weeks'),
            'end_datetime' => Carbon::parse('-2 weeks'),
            'constraint_type_id' => 1,
            'weight' => 0,
            'comment' => '',
            'status' => 1,
            'validated_by' => null,
            'number_of_occurrences' => null,
        ];
    }
}
