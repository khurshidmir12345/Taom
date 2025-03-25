<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MealsController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
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

    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('meals', [MealsController::class, 'index'])->name('admin.meals.index');
    Route::get('food-randomize', [MealsController::class, 'randomize'])->name('users.randomize.index');
    Route::get('roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
