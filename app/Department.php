<?php

namespace App;

use App\Branch;
use App\Workplace;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'description', 'code', 'workplace_id', 'branch_id'];

    public function scopeOwnBranch($query)
    {
        return $query->where('branch_id', \Auth::user()->branch->id);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function workplace()
    {
        return $this->belongsTo(Workplace::class);
    }
}
