<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVote extends Model
{
    const TYPE_LIKE = 'like';
    const TYPE_DISLIKE = 'dislike';

    protected $fillable = [
        'bot_user_id',
        'product_id',
        'type'
    ];

    protected $casts = [
        'type' => 'string'
    ];

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_LIKE,
            self::TYPE_DISLIKE
        ];
    }
} 