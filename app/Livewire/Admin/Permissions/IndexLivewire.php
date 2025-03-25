<?php /** @noinspection ALL */

namespace App\Livewire\Admin\Permissions;

use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;

class IndexLivewire extends Component
{
    use WithPagination;

    public $name_permission;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public function resetForm()
    {
        $this->reset(['name_permission', 'edit_id']);
    }

    public function createOrUpdatePermission()
    {
        $this->validate([
            'name_permission' => 'required|string|unique:permissions,name,' . $this->edit_id,
        ]);

        Permission::updateOrCreate(
            ['id' => $this->edit_id],
            ['name' => $this->name_permission]
        );

        session()->flash('success', $this->edit_id ? 'Permission updated successfully.' : 'Permission created successfully.');

        $this->resetForm();

        $this->dispatch('hidePermissionModal');
    }

    public function delete($id)
    {
        Permission::findOrFail($id)->delete();
        session()->flash('success', 'Permission deleted successfully.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->edit_id = $permission->id;
        $this->name_permission = $permission->name;

        $this->dispatch('showPermissionModal');
    }

    public function render()
    {
        $permissions = Permission::orderByDesc('id')->paginate(20);

        return view('livewire.admin.permissions.index-livewire', compact('permissions'));
    }
}
