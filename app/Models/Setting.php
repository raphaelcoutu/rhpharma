<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public function scopeValueByKey($query, $key)
    {
        return $query->where('key', $key)->firstOrFail()->value;
    }
}
