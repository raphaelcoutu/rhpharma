<?php

namespace Database\Factories;

use App\Models\Workplace;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkplaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workplace::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'province' => $this->faker->name,
            'postal_code' => 'A1B 2C3',
            'code' => $this->faker->stateAbbr()
        ];
    }
}
