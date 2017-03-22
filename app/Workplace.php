<?php

namespace App;

use App\Branch;
use App\Department;
use Illuminate\Database\Eloquent\Model;

class Workplace extends Model
{
    protected $fillable = [
        'name', 'address', 'city', 'province', 'country', 'postal_code', 'code'
    ];
    
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
