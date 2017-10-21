<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'workdays_per_week',
        'is_active',
        'seniority',
        'branch_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeOwnBranch($query)
    {
        return $query->where('branch_id', \Auth::user()->branch->id);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function constraintNotes()
    {
        return $this->belongsToMany(ConstraintNote::class);
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
