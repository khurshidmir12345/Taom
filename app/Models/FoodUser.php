<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodUser extends Model
{
    protected $table = 'food_user';

    protected $fillable = [
        'food_id',
        'user_id',
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
