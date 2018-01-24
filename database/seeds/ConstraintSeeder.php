<?php

use App\Constraint;
use App\ConstraintNote;
use App\Schedule;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConstraintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scheduleStartDate = Schedule::find(5)->start_date;

        //MJL Jour
        factory(Constraint::class)->create([
            'user_id' => 35,
            'start_datetime' => $scheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(1)->setTime(14,0),
            'constraint_type_id' => 2
        ]);

        //MJL AM
        factory(Constraint::class)->create([
            'user_id' => 35,
            'start_datetime' => $scheduleStartDate->copy()->addDays(9)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(9)->setTime(14,0),
            'constraint_type_id' => 2
        ]);

        //MJL PM
        factory(Constraint::class)->create([
            'user_id' => 35,
            'start_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(14,0),
            'constraint_type_id' => 2
        ]);

        //RC JOUR (vendredi sem2)
        factory(Constraint::class)->create([
            'user_id' => 44,
            'start_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(14,0),
            'constraint_type_id' => 1
        ]);
    }
}
