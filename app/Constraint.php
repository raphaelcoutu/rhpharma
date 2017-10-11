<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constraint extends Model
{
    public function constraintType()
    {
        return $this->belongsTo(ConstraintType::class);
    }

    public function scopeFromLoggedInUser($query)
    {
        return $query->where('user_id', \Auth::user()->id);
    }

    public function scopeFixed($query)
    {

    }

    public function scopeAvailability($query)
    {

    }
}
