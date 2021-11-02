<?php

namespace Database\Factories;

use App\Builders\BuildStatus;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'limit_date_weekends' => Carbon::now()->previous('Friday'),
            'limit_date' => Carbon::now()->previous('Friday'),
            'start_date' => Carbon::now()->next('Sunday'),
            'end_date' => Carbon::now()->addWeeks(4)->next('Saturday')->setTime(23,59,59),
            'branch_id' => 1,
            'status_holidays' => BuildStatus::Standby,
            'status_weekends' => BuildStatus::Standby,
            'status_last_evening' => BuildStatus::Standby,
            'status_clinical_departments' => BuildStatus::Standby,
            'notes' => null,
        ];
    }
}
