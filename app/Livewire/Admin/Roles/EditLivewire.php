<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Role;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\On;
use Livewire\Component;

class EditLivewire extends Component
{
    public $name, $id;
    public $permissions = [];

    #[NoReturn] #[On('role-editing')]
    public function updateRole($id)
    {
        $role = Role::query()->find($id);
        if ($role) {
            $this->id = $role->id;
            $this->name = $role->name;
            $this->permissions = $role->permissions->pluck('id')->toArray();
        }
    }

    public function update()
    {
        $role = Role::query()->find($this->id);
        if ($role) {
            $role->name = $this->name;
            $role->permissions()->sync($this->permissions);
            $role->save();
            $this->dispatch('roleUpdated');
            $this->dispatch('hideEditModal');
        }
    }

    public function render()
    {
        return view('livewire.admin.roles.edit-livewire');
    }
}
