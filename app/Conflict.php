<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conflict extends Model
{
    protected $guarded = [];

    protected $dates = ['start_date', 'end_date'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeClearSchedule($query, Schedule $schedule)
    {
        return $query->where('schedule_id', $schedule->id)->delete();
    }
}
