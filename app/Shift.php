<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    public function shiftType()
    {
        return $this->belongsTo(ShiftType::class);
    }
}
