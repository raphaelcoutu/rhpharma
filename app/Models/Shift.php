<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $guarded = [];

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
