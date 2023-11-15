<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conflict extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeClearSchedule($query, Schedule $schedule)
    {
        return $query->where('schedule_id', $schedule->id)->delete();
    }
}
