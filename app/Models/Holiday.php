<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['description', 'date'];

    protected $dates = ['date'];

    public function scopeByDate($query)
    {
        return $query->orderBy('date', 'asc')->where('date', '>=', Carbon::today());
    }

    public function scopeFrom($query, $date)
    {
        return $query->where('date', '>=', $date);
    }

    public function scopeTo($query, $date)
    {
        return $query->where('date', '<=', $date);
    }
}
