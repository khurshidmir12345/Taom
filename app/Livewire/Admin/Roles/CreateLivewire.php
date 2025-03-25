<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class CreateLivewire extends Component
{
    public $name;
    public $permissions = [];

    public function removePermission($permissionId)
    {
        $this->permissions = array_filter($this->permissions, fn($id) => $id != $permissionId);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
        ]);

        $role = Role::query()->create([
            'name' => $this->name,
        ]);

        $role->permissions()->sync($this->permissions);

        $this->dispatch('hideCreateModal');
        $this->dispatch('roleCreated');
        $this->reset();
    }

    public function render()
    {
        $allPermissions = Permission::all()->pluck('name', 'id');
        return view('livewire.admin.roles.create-livewire', ['allPermissions' => $allPermissions]);
    }
}
