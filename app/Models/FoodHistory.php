<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodHistory extends Model
{
    protected $table = 'food_histories';

    protected $fillable = [
        'user_id',
        'food_id',
        'meal_type',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
