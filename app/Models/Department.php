<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'department_type_id', 'workplace_id', 'branch_id',
        'bonus_weeks', 'bonus_pts', 'malus_weeks', 'malus_pts',
        'monday_am', 'monday_pm', 'tuesday_am', 'tuesday_pm', 'wednesday_am', 'wednesday_pm',
        'thursday_am', 'thursday_pm', 'friday_am', 'friday_pm'];

    public function scopeOwnBranch($query): Builder
    {
        return $query->where('branch_id', \Auth::user()->branch->id);
    }

    public function scopeWithActiveUsers($query): Builder
    {
        return $query->with(['users' => function ($query) {
            $query->wherePivot('active', 1);
        }]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['active', 'history', 'planning_long', 'planning_short']);
    }

    public function departmentType(): BelongsTo
    {
        return $this->belongsTo(DepartmentType::class);
    }

    public function workplace(): BelongsTo
    {
        return $this->belongsTo(Workplace::class);
    }
}
