<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vegetable extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name_uz',
        'name_ru',
    ];

    public function foods()
    {
        return $this->belongsToMany(Food::class, 'food_vegetable');
    }

}
