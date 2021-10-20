<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstraintNote extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
