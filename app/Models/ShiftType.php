<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    protected $guarded = [];

    public function scopeOwnBranch($query): Builder
    {
        return $query->where('branch_id', \Auth::user()->branch->id);
    }

    public function getStartTimeStringAttribute(): string
    {
        return substr($this->start_time, 0, -3);
    }

    public function getEndTimeStringAttribute(): string
    {
        return substr($this->end_time, 0, -3);
    }
}
