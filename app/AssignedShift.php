<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedShift extends Model
{
    protected $dates = ['date'];

    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
