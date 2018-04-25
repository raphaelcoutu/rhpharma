<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conflict extends Model
{
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
