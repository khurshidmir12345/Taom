<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReminder extends Model
{
    protected $fillable = [
        'day',
        'message',
        'time',
        'is_active'
    ];

    protected $casts = [
        'time' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, $day)
    {
        return $query->where('day', strtolower($day));
    }
}
