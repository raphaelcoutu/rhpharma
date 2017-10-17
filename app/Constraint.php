<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constraint extends Model
{
    protected $guarded = [];

    public function constraintType()
    {
        return $this->belongsTo(ConstraintType::class);
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
}
