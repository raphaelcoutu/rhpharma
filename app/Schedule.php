<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['name', 'constraint_limit_date', 'start_date', 'end_date', 'branch_id'];

    protected $dates = ['start_date', 'end_date', 'constraint_limit_date'];

    public function scopeOrderedDesc($query)
    {
        return $query->orderBy('end_date', 'desc')->where('branch_id', \Auth::user()->branch->id);
    }

    public function getConstraintLimitDateStringAttribute()
    {
        return $this->constraint_limit_date->toDateString();
    }

    public function getStartDateStringAttribute()
    {
        return $this->start_date->toDateString();
    }

    public function getEndDateStringAttribute()
    {
        return $this->end_date->toDateString();
    }

    public function getDurationInWeeksAttribute()
    {
        // Les semaines sont du dimanche au samedi (6 jours de différence)
        // On doit donc additionner 1 à la semaine.
        return $this->end_date->diffInWeeks($this->start_date) + 1;
    }
}
