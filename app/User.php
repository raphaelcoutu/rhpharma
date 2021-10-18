<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'azure_id',
        'firstname',
        'lastname',
        'email',
        'password',
        'workdays_per_week',
        'is_active',
        'seniority',
        'branch_id',
        'is_manual'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeOwnBranch($query)
    {
        return $query->where('branch_id', \Auth::user()->branch->id);
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getInitialsAttribute()
    {
        $temp = collect(explode(' ', str_replace('-', ' ', $this->getFullnameAttribute())));

        return  $temp->reduce(function ($carry, $partialName) {
            return $carry . mb_substr($partialName, 0, 1, 'utf-8');
        });
    }

    public function assignedShifts()
    {
        return $this->hasMany(AssignedShift::class);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function constraints()
    {
        return $this->hasMany(Constraint::class);
    }

    public function constraintNotes()
    {
        return $this->belongsToMany(ConstraintNote::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class)
            ->withPivot(['active', 'history', 'planning_long', 'planning_short']);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
