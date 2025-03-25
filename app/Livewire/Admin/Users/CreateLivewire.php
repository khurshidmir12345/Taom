<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreateLivewire extends Component
{

    public $name, $email, $password;
    public $roles = [];


    public function removeRole($roleId)
    {
        $this->roles = array_filter($this->roles, fn($id) => $id != $roleId);
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'roles' => 'required|array',
    ];

    public function createUser()
    {
        $this->validate();

        $user = User::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->roles()->sync($this->roles);

        $this->dispatch('hideCreateModal');
        $this->dispatch('userCreated');
        $this->reset();
    }
    public function render()
    {
        return view('livewire.admin.users.create-livewire');
    }
}
