<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MealsController;
use App\Http\Controllers\SocialAuthController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/',[MealsController::class, 'randomize'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('admin-panel', function () {
        return view('admin-panel');
    })->name('admin-panel');

    Route::get('seed', function () {
        $categories = [
            ['name' => 'Suyuq'],
            ['name' => 'Qo\'yiq'],
            ['name' => 'Fast food'],
        ];

        Category::query()->insert($categories);

        return response()->json(['message' => 'Categories seeded successfully.']);
    });

    Route::get('food-randomize', [MealsController::class, 'randomize'])->name('users.randomize.index');
    Route::get('food-history', [MealsController::class, 'history'])->name('users.food.history');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::get('/auth/google', [SocialAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'callback']);

require __DIR__.'/auth.php';

