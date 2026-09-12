<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'limit_date', 'limit_date_weekends', 'start_date', 'end_date', 'branch_id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'limit_date' => 'date',
        'limit_date_weekends' => 'date',
    ];

    public function conflicts(): HasMany
    {
        return $this->hasMany(Conflict::class);
    }

    public function buildMessages(): HasMany
    {
        return $this->hasMany(BuildMessage::class);
    }

    public function scopeOrderedDesc($query): Builder
    {
        return $query->orderBy('end_date', 'desc')->where('branch_id', \Auth::user()->branch->id);
    }

    public function getLimitDateStringAttribute(): string
    {
        return $this->limit_date->toDateString();
    }

    public function getLimitDateWeekendsStringAttribute(): string
    {
        return $this->limit_date_weekends->toDateString();
    }

    public function getStartDateStringAttribute(): string
    {
        return $this->start_date->toDateString();
    }

    public function getEndDateStringAttribute(): string
    {
        return $this->end_date->toDateString();
    }

    public function getDurationInWeeksAttribute(): int
    {
        // Les semaines sont du dimanche au samedi (6 jours de différence)
        // On doit donc additionner 1 à la semaine.
        return $this->end_date->diffInWeeks($this->start_date) + 1;
    }
}
