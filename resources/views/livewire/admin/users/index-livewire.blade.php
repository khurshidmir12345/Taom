<div>
    <div class="container">
        <!-- User List -->
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="align-items-center mt-3 ">Users List</h4>
                    <button class="btn btn-outline-success text-dark mb-2"
                            style="font-size: medium"
                            data-bs-toggle="modal"
                            data-bs-target="#createUserModal">
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
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>   @foreach($user->roles as $role)
                                    <span class="badge bg-success rounded px-2 py-1 text-white"
                                          style="font-size: 12px;">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <button wire:click="edit({{ $user->id }})" class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal">Edit
                                </button>
                                <button wire:click="delete({{ $user->id }})" class="btn btn-danger btn-sm">Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No users found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
