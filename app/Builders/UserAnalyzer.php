<?php

namespace App\Builders;

use App\AssignedShift;
use App\Conflict;
use App\Schedule;
use App\User;

class UserAnalyzer
{
    protected $schedule;
    protected $shifts;
    protected $conflicts;

    private function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
        $this->users = User::where('is_active', 1)->get();
        $this->shifts = AssignedShift::with('shift')->inDateInterval($schedule->start_date, $schedule->end_date)->get();

        $this->conflicts = collect([]);
    }

    public static function run(Schedule $schedule)
    {
        (new self($schedule))
            ->analyzeWorkdaysPerWeek()
            ->insertConflicts();
    }

    private function analyzeWorkdaysPerWeek()
    {
        $durationInWeeks = $this->schedule->durationInWeeks;

        foreach($this->users as $user) {
            $workdays = $user->workdays_per_week;

            for ($i = 0; $i < $durationInWeeks; $i++) {
                $iterateFirstDay = $this->schedule->start_date->copy()->addDays(7*$i);
                $iterateLastDay = $this->schedule->start_date->copy()->addDays(7*$i + 6)->setTime(23,59,59);

                $weekShifts = $this->shifts->where('user_id', $user->id)
                    ->where('date', '>=', $iterateFirstDay)
                    ->where('date', '<=', $iterateLastDay)
                    ->where('shift.department_id', '!=',20);

                if($weekShifts->count() > $workdays) {
                    $newConflict = new Conflict([
                        'schedule_id' => $this->schedule->id,
                        'department_id' => null,
                        'start_date' => $iterateFirstDay,
                        'end_date' => $iterateLastDay,
                        'message' => "{$user->firstname} {$user->lastname} devrait travailler {$workdays} jours!"
                    ]);
                    $this->conflicts->push($newConflict);
                }
            }
        }

        return $this;
    }

    private function insertConflicts()
    {
        Conflict::insert($this->conflicts->toArray());
    }
}