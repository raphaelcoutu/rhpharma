<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workplace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'city', 'province', 'country', 'postal_code', 'code'
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
