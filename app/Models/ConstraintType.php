<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstraintType extends Model
{
    protected $fillable = [
        'azure_id',
        'name',
        'description',
        'code',
        'is_work',
        'is_single_day',
        'is_group_constraint',
        'is_day_in_schedule',
        'branch_id'
    ];

    public function criteria()
    {
        return $this->belongsToMany(Criterion::class);
    }

    public function scopeOwnBranch($query)
    {
        return $query->where('branch_id', \Auth::user()->branch->id);
    }
}
