<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'likes_number',
        'dislikes_number',
        'category_id',
        'cafe_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'likes_number' => 'integer',
        'dislikes_number' => 'integer'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ProductVote::class);
    }
} 