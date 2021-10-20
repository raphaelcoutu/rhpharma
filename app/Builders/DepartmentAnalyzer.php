<?php

namespace app\Builders;

use App\Models\AssignedShift;
use App\Models\Conflict;
use App\Models\Holiday;
use App\Models\Schedule;

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
            ->save();
    }

    private function analyzeMissings()
    {
        $duration = $this->schedule->start_date->diffInDays($this->schedule->end_date) + 1;
        $conflictRange = collect([]);

        for ($i = 0; $i < $duration; $i++) {
            $iterateDay = $this->schedule->start_date->copy()->addDays($i);
            $newConflict = null;

            // Si le jour contient un shift
            // Si le jour n'est pas un week end
            // Si le jour est un férié
            // Si département VIH et que le vendredi est vide
            // => On skip.
            if(!$this->shifts->contains('date', $iterateDay) && !in_array($iterateDay->format('w'), ["0", "6"], true)
                && !$this->holidays->contains('date', $iterateDay)
                && !($this->departmentId === 7 && $iterateDay->dayOfWeek === 5)) {
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
            }

            // Si le range est vide et qu'on a un conflit => on remplit
            // Si le range n'est pas vide et qu'on a un conflit => on remplit encore
            if (($newConflict != null && $conflictRange->isEmpty())
            || ($newConflict != null && $conflictRange->isNotEmpty() && $conflictRange->last()->start_date->diffInDays($iterateDay) <= 1)) {
                $conflictRange->push($newConflict);
            }

            // Si le range n'est pas vide et qu'on a plus d'erreur, on push les conflits
            else if ($newConflict == null && $conflictRange->isNotEmpty()) {
                // On choisi le premier conflit et on lui ajoute une date de fin.
                $conflict = $conflictRange->first();
                if($conflictRange->count() > 1) {
                    $conflict->end_date = $conflictRange->last()->start_date;
                    $conflict->severity = 1;
                }

                if($conflictRange->count() > 3) {
                    $conflict->severity = 2;
                }

                $this->conflicts->push($conflict);

                $conflictRange = collect([]);
            }
        }

        return $this;
    }

    private function save()
    {
        Conflict::insert($this->conflicts->toArray());
    }
}
