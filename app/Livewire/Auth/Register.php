<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['type'] = $validated['email'] == 'mirzajonovx86@gmail.com' ? 'admin' : 'user';

        $user = User::query()->create($validated);

        event(new Registered($user));

        Auth::login($user);


        if ($user->type === 'admin') {
            $this->redirect(route('admin-panel', absolute: false), navigate: true);
        } else {
            $this->redirect(route('users.randomize.index', absolute: false), navigate: true);
        }
    }
}
