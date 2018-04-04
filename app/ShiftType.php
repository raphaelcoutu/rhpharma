<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    public function getStartTimeStringAttribute()
    {
        return substr($this->start_time, 0, -3);
    }

    public function getEndTimeStringAttribute()
    {
        return substr($this->end_time, 0, -3);
    }
}
