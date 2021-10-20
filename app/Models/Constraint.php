<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Constraint extends Model
{
    protected $guarded = [];

    protected $dates = ['start_datetime', 'end_datetime'];

    public function constraintType()
    {
        return $this->belongsTo(ConstraintType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function scopeFromLoggedInUser($query)
    {
        return $query->where('user_id', \Auth::user()->id);
    }

    public function scopeFixedConstraints($query)
    {
        return $query->whereHas('constraintType', function($query) {
                $query->where('is_group_constraint', '=',0);
            });
    }

    public function scopeAvailabilityConstraints($query)
    {
        return $query->whereHas('constraintType', function($query) {
                $query->where('is_group_constraint', '=',1);
            });
    }

    public function scopeUnvalidated($query)
    {
        return $query->where('status', 0);
    }

    public function scopeInDateInterval($query, Carbon $start_date, Carbon $end_date)
    {
        return $query->where(function ($query) use ($start_date, $end_date) {
            $query->where('start_datetime', '>=', $start_date->copy()->setTime(0,0))
            ->where('start_datetime', '<=', $end_date->copy()->setTime(23,59));
        })->orWhere(function ($query) use ($start_date, $end_date) {
            $query->where('start_datetime', '<', $end_date->copy()->setTime(23,59))
                ->where('end_datetime', '>', $start_date->copy()->setTime(0,0));
        });
    }
}
