<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BotUser extends Model
{
    protected $fillable = [
        'chat_id',
        'user_name',
        'first_name',
        'last_name',
        'last_message_id',
        'cafe_id',
        'step',
        'previous_step',
    ];

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ProductVote::class);
    }
} 