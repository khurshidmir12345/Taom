<div wire:ignore.self class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form wire:submit.prevent="updateUser">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" wire:model="name" class="form-control">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" wire:model="email" class="form-control">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="roles" class="form-label">Roles</label>
                        <select multiple wire:model.live="roles" class="form-control">
                            @foreach(\App\Models\Role::all() as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-3">
                            <strong>Selected Roles:</strong>
                            <div>
                                @foreach($roles as $roleId)
                                    @php
                                        $role = \App\Models\Role::find($roleId);
                                    @endphp
                                    @if($role)
                                        <span class="badge bg-success me-1">
                                            {{ $role->name }}
                                            <button type="button" class="btn btn-sm btn-light ms-1 p-0"
                                                    wire:click="removeRole({{ $roleId }})"
                                                    style="border: none; background: transparent; color: white;">×</button>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
