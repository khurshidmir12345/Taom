<div>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="store">
        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" wire:model="name" placeholder="Enter role name">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <select multiple wire:model.live="permissions" style="width: 100%">
                @foreach(\App\Models\Permission::all() as $permission)
                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                @endforeach
            </select>
            <div class="mt-3">
                <strong>Tanlangan ruxsatlar:</strong>
                <div>
                    @foreach($permissions as $permissionId)
                        @php
                            $perm = \App\Models\Permission::find($permissionId);
                        @endphp
                        @if($perm)
                            <span class="badge bg-success me-1">
                            {{ $perm->name }}
                            <button type="button" class="btn btn-sm btn-light ms-1 p-0" wire:click="removePermission({{ $permissionId }})" style="border: none; background: transparent; color: white;">×</button>
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-outline-primary text-dark end">Create Role</button>
    </form>
</div>
