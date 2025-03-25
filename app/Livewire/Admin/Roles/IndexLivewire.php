<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class IndexLivewire extends Component
{
    use withPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'roleCreated' => 'refreshRoles',
        'roleUpdated' => 'refreshRoles',
    ];

    public function edit($id)
    {
        $this->dispatch('role-editing', id: $id);
    }

    public function refreshRoles()
    {
        $this->render();
    }

    public function delete($id)
    {
        Role::query()->findOrFail($id)->delete();
    }


    public function render()
    {
        $roles = Role::query()->with('permissions')->orderByDesc('id')->paginate(20);
        return view('livewire.admin.roles.index-livewire', compact('roles'));
    }
}
