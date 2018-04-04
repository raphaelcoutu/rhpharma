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
        $scheduleStartDate = Schedule::find(5)->start_date;

        // Weekends

        $this->addAssignedShifts([24,16,19,35,27], $scheduleStartDate);

        $this->addAssignedShifts([14,39,7,33,47], $scheduleStartDate->addDays(6));
        $this->addAssignedShifts([14,39,7,33,47], $scheduleStartDate->addDays(1));

        $this->addAssignedShifts([13,11,45,8,37], $scheduleStartDate->addDays(6));
        $this->addAssignedShifts([13,11,45,8,37], $scheduleStartDate->addDays(1));

        $this->addAssignedShifts([17,49,23,20,5], $scheduleStartDate->addDays(6));
        $this->addAssignedShifts([17,49,23,20,5], $scheduleStartDate->addDays(1));

        $this->addAssignedShifts([10,45,40,50,38], $scheduleStartDate->addDays(6));
        $this->addAssignedShifts([10,45,40,50,38], $scheduleStartDate->addDays(1));

        $this->addAssignedShifts([32,25,18,26,24], $scheduleStartDate->addDays(6));
        $this->addAssignedShifts([32,25,18,26,24], $scheduleStartDate->addDays(1));
    }

    private function addAssignedShifts(Array $users, $date) {
        collect($users)->each(function ($user) use ($date) {
            AssignedShift::create([
                'user_id' => $user,
                'shift_type_id' => 1,
                'department_id' => 18,
                'is_generated' => 0,
                'is_published' => 0,
                'date' => $date,
            ]);
        });
    }
}
