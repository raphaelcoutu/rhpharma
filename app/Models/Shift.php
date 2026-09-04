<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $guarded = [];

    public function shiftType(): BelongsTo
    {
        return $this->belongsTo(ShiftType::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedShifts(): HasMany
    {
        return $this->hasMany(AssignedShift::class);
    }
}
