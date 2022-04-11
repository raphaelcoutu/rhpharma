<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedShift extends Model
{
    use HasFactory;

    protected $dates = ['date', 'created_at', 'updated_at'];

    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeClearSchedule($query, Schedule $schedule)
    {
        return $query->where('date', '>=', $schedule->start_date)
            ->where('date', '<=', $schedule->end_date)
            ->where('is_generated', 1)
            ->where('is_published', 0)
            ->delete();
    }

    public function scopeInDateInterval($query, Carbon $start_date, Carbon $end_date)
    {
        return $query->where(function ($query) use ($start_date, $end_date) {
            $query->where('date', '>=', $start_date->setTime(0,0))
                ->where('date', '<=', $end_date->setTime(23,59));
        });
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
