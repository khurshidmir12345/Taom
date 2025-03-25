<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Foods</h5>
            <button wire:click="create" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add New Food
            </button>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name (UZ)</th>
                        <th>Name (RU)</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($foods as $food)
                        <tr>
                            <td>{{ $food->id }}</td>
                            <td>
                                @if($food->getFirstMedia('food'))
                                    <img src="{{ $food->getFirstMediaUrl('food') }}" alt="{{ $food->name_uz }}" width="50">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ $food->name_uz }}</td>
                            <td>{{ $food->name_ru }}</td>
                            <td>{{ $food->category->name ?? 'No Category' }}</td>
                            <td>
                                <button wire:click="edit({{ $food->id }})" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button wire:click="delete({{ $food->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this food?')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No foods found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $foods->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($isOpen)
        <div class="modal show d-block" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEdit ? 'Edit Food' : 'Create Food' }}</h5>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="name_uz">Name (UZ)</label>
                                <input type="text" class="form-control" id="name_uz" wire:model="name_uz">
                            </div>

                            <div class="form-group">
                                <label for="name_ru">Name (RU)</label>
                                <input type="text" class="form-control" id="name_ru" wire:model="name_ru">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" wire:model="description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" id="category" wire:model="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control-file" id="image" wire:model="image">

                                @if ($image)
                                    <div class="mt-2">
                                        <p>Preview:</p>
                                        <img src="{{ $image->temporaryUrl() }}" width="100">
                                    </div>
                                @elseif ($isEdit && $food_id)
                                    @php $food = \App\Models\Food::find($food_id); @endphp
                                    @if($food && $food->getFirstMedia('food'))
                                        <div class="mt-2">
                                            <p>Current Image:</p>
                                            <img src="{{ $food->getFirstMediaUrl('food') }}" width="100">
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                        @if($isEdit)
                            <button type="button" class="btn btn-primary" wire:click="update">Update</button>
                        @else
                            <button type="button" class="btn btn-primary" wire:click="store">Save</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
