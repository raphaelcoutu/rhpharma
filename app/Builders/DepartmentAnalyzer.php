<?php

namespace app\Builders;

use App\AssignedShift;
use App\Conflict;
use App\Holiday;
use App\Schedule;

class DepartmentAnalyzer
{
    protected $schedule;
    protected $departmentId;
    protected $holidays;
    protected $shifts;

    protected $conflicts;

    private function __construct(Schedule $schedule, $departmentId)
    {
        $this->schedule = $schedule;
        $this->departmentId = $departmentId;

        $this->conflicts = collect([]);

        $this->holidays = Holiday::from($this->schedule->start_date)->to($this->schedule->end_date)->get();
        $this->shifts = AssignedShift::whereHas('shift', function ($query) {
            $query->where('department_id', $this->departmentId);
        })->inDateInterval($this->schedule->start_date, $this->schedule->end_date)
            ->get();
    }

    public static function run(Schedule $schedule, $departmentId)
    {
        (new self($schedule, $departmentId))
            ->analyzeMissings()
            ->insertConflicts();
    }

    private function analyzeMissings()
    {
        $duration = $this->schedule->start_date->diffInDays($this->schedule->end_date) + 1;
        $conflictRange = collect([]);

        for ($i = 0; $i < $duration; $i++) {
            $iterateDay = $this->schedule->start_date->copy()->addDays($i);

            if($this->shifts->contains('date', $iterateDay)) continue;

            // Si le jour n'est pas un week end
            if(in_array($iterateDay->format('w'), ["0", "6"], true)) continue;

            // Si le jour est un férié, on skip.
            if($this->holidays->contains('date', $iterateDay)) continue;

            // Si département VIH, ne rien faire pour le vendredi s'il manque quelqu'un
            if($this->departmentId === 7 && $iterateDay->dayOfWeek === 5) continue;

            $newConflict = new Conflict([
                'schedule_id' => $this->schedule->id,
                'department_id' => $this->departmentId,
                'severity' => 0,
                'start_date' => $iterateDay->toDateString(),
                'end_date' => null,
                'message' => 'Pharmacien manquant',
                'created_at' => new \DateTime,
                'updated_at' => new \DateTime
            ]);

            // Si le range est vide, on commence par le remplir
            if($conflictRange->isEmpty()) {
                $conflictRange->push($newConflict);
            } else if($conflictRange->isNotEmpty() && $conflictRange->last()->start_date->diffInDays($iterateDay) <= 1) {
                $conflictRange->push($newConflict);
            } else {
                // On choisi le premier conflit et on lui ajoute une date de fin.
                $conflict = $conflictRange->first();
                if($conflictRange->count() > 1) {
                    $conflict->end_date = $conflictRange->last()->start_date;

                    $conflict->severity = 1;

                    if($conflictRange->count() > 3) {
                        $conflict->severity = 2;
                    }
                }

                $this->conflicts->push($conflict);

                $conflictRange = collect([]);
            }
        }

        return $this;
    }

    private function insertConflicts()
    {
        Conflict::insert($this->conflicts->toArray());
    }
}