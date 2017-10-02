<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['description', 'date'];

    public function scopeByDate($query)
    {
        return $query->orderBy('date', 'asc')->where('date', '>=', Carbon::today());
    }
}
