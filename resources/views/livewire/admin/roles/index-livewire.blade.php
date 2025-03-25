<div>
    <div class="container">
        <!-- User List -->
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="align-items-center mt-3 ">Roles List</h4>
                    <button class="btn btn-outline-success text-dark mb-2"
                            style="font-size: medium"
                            data-bs-toggle="modal"
                            data-bs-target="#CreateRoleModal">
                        Create Role
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-success rounded px-2 py-1 text-white"
                                          style="font-size: 12px;">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <button wire:click="edit({{ $role->id }})" data-bs-toggle="modal"
                                        data-bs-target="#editRoleModal"
                                        class="btn btn-warning btn-sm">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $role->id }})" class="btn btn-danger btn-sm">Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No roles found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

