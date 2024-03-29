<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'department_type_id', 'workplace_id', 'branch_id',
        'bonus_weeks', 'bonus_pts', 'malus_weeks', 'malus_pts',
        'monday_am', 'monday_pm', 'tuesday_am', 'tuesday_pm', 'wednesday_am', 'wednesday_pm',
        'thursday_am', 'thursday_pm', 'friday_am', 'friday_pm'];

    public function scopeOwnBranch($query)
    {
        return $query->where('branch_id', \Auth::user()->branch->id);
    }

    public function scopeWithActiveUsers($query)
    {
        return $query->with(['users' => function($query) {
            $query->wherePivot('active', 1);
        }]);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['active', 'history', 'planning_long', 'planning_short']);
    }

    public function departmentType()
    {
        return $this->belongsTo(DepartmentType::class);
    }

    public function workplace()
    {
        return $this->belongsTo(Workplace::class);
    }
}
