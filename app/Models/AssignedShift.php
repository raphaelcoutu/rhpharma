<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignedShift extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $guarded = [];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeClearSchedule($query, Schedule $schedule): int
    {
        return $query->where('date', '>=', $schedule->start_date)
            ->where('date', '<=', $schedule->end_date)
            ->where('is_generated', 1)
            ->where('is_published', 0)
            ->delete();
    }

    public function scopeInDateInterval($query, Carbon $start_date, Carbon $end_date): Builder
    {
        return $query->where(function ($query) use ($start_date, $end_date) {
            $query->where('date', '>=', $start_date->setTime(0, 0))
                ->where('date', '<=', $end_date->setTime(23, 59));
        });
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
