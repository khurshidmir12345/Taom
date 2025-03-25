<div>
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <h4 class="mt-3">Permissions List</h4>
                <button onclick="Livewire.dispatch('showPermissionModal')" class="btn btn-outline-success text-dark mb-2">Create Permission</button>
            </div>

            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <button wire:click="edit({{ $permission->id }})" class="btn btn-info btn-sm">Edit</button>
                                <button wire:click="delete({{ $permission->id }})" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No permissions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-3">{{ $permissions->links() }}</div>
            </div>
        </div>

        <div id="permissionModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $edit_id ? 'Edit' : 'Create' }} Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form wire:submit.prevent="createOrUpdatePermission">
                            <div class="mb-3">
                                <label for="name_permission" class="form-label">Name</label>
                                <input type="text" id="name_permission" wire:model="name_permission" class="form-control">
                                @error('name_permission')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">{{ $edit_id ? 'Update' : 'Create' }} Permission</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
