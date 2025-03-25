<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Food extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'foods';
    protected $fillable = [
        'name_uz',
        'name_ru',
        'description',
        'category_id',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foods')->singleFile();
    }
    public function vegetables()
    {
        return $this->belongsToMany(Vegetable::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function users()
    {
        $this->belongsToMany(User::class, 'food_user');
    }
}
