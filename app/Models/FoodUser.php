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
}
