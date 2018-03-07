<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedShift extends Model
{
    protected $dates = ['date'];

    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
