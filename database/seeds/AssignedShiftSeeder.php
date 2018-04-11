<?php

use App\AssignedShift;
use App\Schedule;
use Illuminate\Database\Seeder;

class AssignedShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scheduleStartDate = Schedule::find(5)->start_date->addDays(-1);

        // Weekends

        $this->addWeekend([24,16,19,35,27], $scheduleStartDate);

        $this->addWeekend([14,39,7,33,47], $scheduleStartDate->addDays(7));

        $this->addWeekend([13,11,45,8,37], $scheduleStartDate->addDays(7));

        $this->addWeekend([17,49,23,20,5], $scheduleStartDate->addDays(7));

        $this->addWeekend([10,45,40,50,38], $scheduleStartDate->addDays(7));

        $this->addWeekend([32,25,18,26,24], $scheduleStartDate->addDays(7));
    }

    private function addWeekend(Array $users, $date) {
        $shifts = [2,3,4,5,8];

        collect($users)->each(function ($user, $key) use ($date, $shifts) {
            AssignedShift::create([
                'user_id' => $user,
                'shift_id' => $shifts[$key],
                'is_generated' => 0,
                'is_published' => 0,
                'date' => $date,
            ]);

            AssignedShift::create([
                'user_id' => $user,
                'shift_id' => $shifts[$key],
                'is_generated' => 0,
                'is_published' => 0,
                'date' => $date->copy()->addDays(1),
            ]);

            if($key >= 0 && $key < 3) {
                AssignedShift::create([
                    'user_id' => $user,
                    'shift_id' => 1,
                    'is_generated' => 0,
                    'is_published' => 0,
                    'date' => $date->copy()->addDays(2), // Lundi
                ]);

                AssignedShift::create([
                    'user_id' => $user,
                    'shift_id' => 1,
                    'is_generated' => 0,
                    'is_published' => 0,
                    'date' => $date->copy()->addDays(3), // Mardi
                ]);

            } else {
                AssignedShift::create([
                    'user_id' => $user,
                    'shift_id' => 1,
                    'is_generated' => 0,
                    'is_published' => 0,
                    'date' => $date->copy()->addDays(5), // Jeudi
                ]);

                AssignedShift::create([
                    'user_id' => $user,
                    'shift_id' => 1,
                    'is_generated' => 0,
                    'is_published' => 0,
                    'date' => $date->copy()->addDays(6), // Vendredi
                ]);
            }
        });
    }
}
