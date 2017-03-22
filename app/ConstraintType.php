<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConstraintType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'code',
        'is_work',
        'is_single_day',
        'is_group_constraint',
        'is_day_in_schedule',
        'branch_id'
    ];
}
