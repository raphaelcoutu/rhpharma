<?php

namespace Database\Factories;

use App\Models\AssignedShift;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignedShiftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssignedShift::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shift_type_id' => 1,
            'is_generated' => 1,
            'is_published' => 0,
        ];
    }
}
