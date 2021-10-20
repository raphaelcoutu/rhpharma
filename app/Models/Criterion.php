<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    public function criterionable()
    {
        return $this->morphTo();
    }
}
