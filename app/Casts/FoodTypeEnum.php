<?php

namespace App\Casts;

enum FoodTypeEnum: string
{
    case MEAT = 'meat';
    case MEATSIZ = 'meatsiz';

    /**
     * Enum qiymatini matnga aylantirish.
     */
    public function toString(): string
    {
        return match ($this) {
            self::MEAT => 'Go\'shtli',
            self::MEATSIZ => 'Go\'shtsiz',
        };
    }

    /**
     * Enumning tasvirlangan qiymatini olish.
     */
    public static function fromString(string $value): self
    {
        return match ($value) {
            'meat' => self::MEAT,
            'meatsiz' => self::MEATSIZ,
            default => throw new \InvalidArgumentException("Invalid food type: $value"),
        };
    }
}
