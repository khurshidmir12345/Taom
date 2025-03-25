<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Categories Management</h4>
                        <button type="button" wire:click="create" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Add New Category
                        </button>
                    </div>

                    @if (session()->has('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search categories...">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->created_at ? $category->created_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <button type="button" wire:click="edit({{ $category->id }})" class="btn btn-info btn-sm">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </button>
                                        <button type="button" wire:click="openDeleteConfirmation({{ $category->id }})" class="btn btn-danger btn-sm">
                                            <i class="mdi mdi-delete"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No categories found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($isOpen)
        <div class="modal fade show" id="categoryModal" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $formMode === 'create' ? 'Create Category' : 'Edit Category' }}</h5>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" wire:model="name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        @if($formMode === 'create')
                            <button type="button" wire:click="store" class="btn btn-primary">Create</button>
                        @else
                            <button type="button" wire:click="update" class="btn btn-primary">Update</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($isDeleteConfirmationOpen)
        <div class="modal fade show" id="deleteModal" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="close" wire:click="closeDeleteConfirmation">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this category? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDeleteConfirmation">Cancel</button>
                        <button type="button" wire:click="delete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Prevent conflicts with other JavaScript libraries
    document.addEventListener('DOMContentLoaded', function() {
        // Make sure body doesn't get multiple overflow:hidden styles
        if (document.querySelectorAll('.modal.show').length > 0) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    });
</script>

