<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cafe extends Model
{
    protected $fillable = [
        'name',
        'bot_token',
        'is_paid'
    ];

    protected $casts = [
        'is_paid' => 'boolean'
    ];

    public function botUsers(): HasMany
    {
        return $this->hasMany(BotUser::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
} 