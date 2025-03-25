<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MealsController extends Controller
{
    public function index()
    {
        return view('admin.meals.index');
    }

    public function randomize()
    {
        return view('users.food.index');
    }
}
