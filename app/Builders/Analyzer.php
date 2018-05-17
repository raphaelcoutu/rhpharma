<?php

namespace app\Builders;

use App\AssignedShift;
use App\Holiday;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Analyzer
{
    protected $schedule;
    protected $departmentId;
    protected $holidays;

    private function __construct(Schedule $schedule, $departmentId)
    {
        $this->schedule = $schedule;
        $this->departmentId = $departmentId;
    }

    public static function conflicts(Schedule $schedule, $departmentId)
    {
        (new self($schedule, $departmentId))->analyzeConflicts();
    }

    private function analyzeConflicts()
    {
        $this->holidays = Holiday::from($this->schedule->start_date)->to($this->schedule->end_date)->get()->pluck('date');
        $shifts = AssignedShift::whereHas('shift', function ($query) {
            $query->where('department_id', $this->departmentId);
        })->inDateInterval($this->schedule->start_date, $this->schedule->end_date)
            ->get()->map(function ($item) {
                return $item->date->format('Y-m-d');
            });

        $duration = $this->schedule->start_date->diffInDays($this->schedule->end_date) + 1;
        $days = collect([]);
        for ($i = 0; $i < $duration; $i++) {
            $iterateDay = $this->schedule->start_date->copy()->addDays($i);

            if(!in_array($iterateDay->format('w'), ["0", "6"], true)) {
                $days->push($this->schedule->start_date->addDays($i)->format('Y-m-d'));
            }
        }

        $diff = $days->diff($shifts)->reject(function ($date) {
            // TODO: Ne pas poper d'alarmes si c'est un jour férié (pour l'instant)
            if($this->holidays->contains($date)) {
                return true;
            }

            //todo: bug fix temp pour enlever conflit si vendredi en VIH
            if($this->departmentId == 7) {
                return Carbon::parse($date)->dayOfWeek == 5;
            }

            return false;
        })->map(function ($date) {
            return [
                'schedule_id' => $this->schedule->id,
                'department_id' => $this->departmentId,
                'date' => $date,
                'message' => 'CONFLICT',
                'created_at' => new \DateTime,
                'updated_at' => new \DateTime
            ];
        });

        \App\Conflict::insert($diff->toArray());
    }
}