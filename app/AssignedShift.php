<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AssignedShift extends Model
{
    protected $dates = ['date'];

    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeInDateInterval($query, Carbon $start_date, Carbon $end_date)
    {
        return $query->where(function ($query) use ($start_date, $end_date) {
            $query->where('date', '>=', $start_date->setTime(0,0))
                ->where('date', '<=', $end_date->setTime(23,59));
        });
    }
}
