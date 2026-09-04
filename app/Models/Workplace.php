<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workplace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'city', 'province', 'country', 'postal_code', 'code',
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
