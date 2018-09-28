<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    public function shiftType()
    {
        return $this->belongsTo(ShiftType::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedShifts()
    {
        return $this->hasMany(AssignedShift::class);
    }
}
