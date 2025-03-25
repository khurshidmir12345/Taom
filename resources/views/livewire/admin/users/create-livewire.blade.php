<div>
    <form wire:submit.prevent="createUser">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" wire:model="name" class="form-control">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" wire:model="email" class="form-control">
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" wire:model="password" class="form-control">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <select multiple wire:model.live="roles" style="width: 100%">
                @foreach(\App\Models\Role::all() as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <div class="mt-3">
                <strong>Tanlangan role lar:</strong>
                <div>
                    @foreach($roles as $roleId)
                        @php
                            $perm = \App\Models\Role::query()->find($roleId);
                        @endphp
                        @if($perm)
                            <span class="badge bg-success me-1">
                            {{ $perm->name }}
                            <button type="button" class="btn btn-sm btn-light ms-1 p-0" wire:click="removeRole({{ $roleId }})" style="border: none; background: transparent; color: white;">×</button>
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>

