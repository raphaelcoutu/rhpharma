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
        $pastScheduleStartDate = Schedule::find(1)->start_date;
        $scheduleStartDate = Schedule::find(5)->start_date;

        $MJL = User::whereFirstname('Marie-Josée')->first();
        $RC = User::whereFirstname('Raphaël')->first();
        $SL = User::whereLastname('Letendre')->first();

        //SU - validé (vieil horaire)
        factory(Constraint::class)->create([
            'user_id' => 1,
            'start_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //SU - non validé (vieil horaire)
        factory(Constraint::class)->create([
            'user_id' => 1,
            'start_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(14,0),
            'constraint_type_id' => 2,
            'status' => 0,
            'validated_by' => null
        ]);

        //SU - validé superpose (vieil horaire)
        factory(Constraint::class)->create([
            'user_id' => 1,
            'start_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $pastScheduleStartDate->copy()->addDays(20)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //MJL Jour
        factory(Constraint::class)->create([
            'user_id' => $MJL->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(1)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //MJL AM
        factory(Constraint::class)->create([
            'user_id' => $MJL->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(9)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(9)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //MJL PM
        factory(Constraint::class)->create([
            'user_id' => $MJL->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //RC JOUR (vendredi sem2)
        factory(Constraint::class)->create([
            'user_id' => $RC->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(14,0),
            'constraint_type_id' => 1,
            'validated_by' => $SL->id
        ]);

        factory(Constraint::class)->create([
           'user_id' => $RC->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(25)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(25)->setTime(16,0),
            'constraint_type_id' => 1,
            'status' => 0
        ]);
    }
}
