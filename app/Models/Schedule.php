<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['name', 'limit_date', 'limit_date_weekends', 'start_date', 'end_date', 'branch_id'];

    protected $dates = ['start_date', 'end_date', 'limit_date', 'limit_date_weekends'];

    public function conflicts()
    {
        return $this->hasMany(Conflict::class);
    }

    public function scopeOrderedDesc($query)
    {
        return $query->orderBy('end_date', 'desc')->where('branch_id', \Auth::user()->branch->id);
    }

    public function getLimitDateStringAttribute()
    {
        return $this->limit_date->toDateString();
    }

    public function getLimitDateWeekendsStringAttribute()
    {
        return $this->limit_date_weekends->toDateString();
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
