<?php

namespace App\Livewire\Admin\Users;

use App\Models\Role;
use App\Models\User;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\On;
use Livewire\Component;

class EditLivewire extends Component
{
    public $userId, $name, $email, $roles = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'roles' => 'array',
        ];
    }
    public function removeRole($roleId)
    {
        $this->roles = array_filter($this->roles, fn($id) => $id != $roleId);
    }

    #[On('user-editing')]
    public function editUser($id)
    {
        $user = User::query()->find($id);
        $this->userId = $user[0]->id;
        $this->name = $user[0]->name;
        $this->email = $user[0]->email;


        $this->dispatch('showEditModal');
    }

    public function updateUser()
    {
        $this->validate();
        $user = User::query()->findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $user->roles()->sync($this->roles);

        $this->reset();
        $this->dispatch('userUpdated');
        $this->dispatch('hideEditModal');
    }

    public function render()
    {
        return view('livewire.admin.users.edit-livewire');
    }
}
