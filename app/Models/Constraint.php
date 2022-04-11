<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Constraint extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['start_datetime', 'end_datetime', 'created_at', 'updated_at'];

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
