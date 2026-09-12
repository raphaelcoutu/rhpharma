<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuildMessage extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_id', 'message'];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
