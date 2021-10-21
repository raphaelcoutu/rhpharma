<?php

namespace Database\Seeders;

use App\Models\Constraint;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConstraintSeeder extends Seeder
{
    public function create(array $constraint)
    {
        return Constraint::create([
            'user_id' => $constraint['user_id'] ?? 1,
            'start_datetime' => $constraint['start_datetime'] ?? Carbon::parse('-2 weeks'),
            'end_datetime' => $constraint['end_datetime'] ?? Carbon::parse('-2 weeks'),
            'constraint_type_id' => $constraint['constraint_type_id'] ?? 1,
            'weight' => $constraint['weight'] ?? 0,
            'comment' => '',
            'status' => $constraint['status'] ?? 1,
            'validated_by' => $constraint['validated_by'] ?? null,
            'number_of_occurrences' => $constraint['number_of_occurences'] ?? null
        ]);
    }

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
        $this->create([
            'user_id' => 1,
            'start_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //SU - non validé (vieil horaire)
        $this->create([
            'user_id' => 1,
            'start_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(14,0),
            'constraint_type_id' => 2,
            'status' => 0,
            'validated_by' => null
        ]);

        //SU - validé superpose (vieil horaire)
        $this->create([
            'user_id' => 1,
            'start_datetime' => $pastScheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $pastScheduleStartDate->copy()->addDays(20)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //MJL Jour
        $this->create([
            'user_id' => $MJL->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(1)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(1)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //MJL AM
        $this->create([
            'user_id' => $MJL->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(9)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(9)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //MJL PM
        $this->create([
            'user_id' => $MJL->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(14,0),
            'constraint_type_id' => 2,
            'validated_by' => $SL->id
        ]);

        //RC JOUR (vendredi sem2)
        $this->create([
            'user_id' => $RC->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(12)->setTime(14,0),
            'constraint_type_id' => 1,
            'validated_by' => $SL->id
        ]);

        $this->create([
           'user_id' => $RC->id,
            'start_datetime' => $scheduleStartDate->copy()->addDays(25)->setTime(8,0),
            'end_datetime' => $scheduleStartDate->copy()->addDays(25)->setTime(16,0),
            'constraint_type_id' => 1,
            'status' => 0
        ]);
    }
}
