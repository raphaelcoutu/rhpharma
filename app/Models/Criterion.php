<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Criterion extends Model
{
    public function criterionable(): MorphTo
    {
        return $this->morphTo();
    }
}
