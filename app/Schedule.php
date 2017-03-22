<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['name', 'constraint_limit_date', 'start_date', 'end_date', 'branch_id'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('end_date', 'desc')->where('branch_id', \Auth::user()->branch->id);
    }
}
