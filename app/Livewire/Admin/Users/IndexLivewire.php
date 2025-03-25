<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class IndexLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'userCreated' => '$refresh',
        'userUpdated' => '$refresh',
    ];

    public function edit($id)
    {
        $this->dispatch('user-editing', ['id' => $id]);
    }

    public function delete($id)
    {
        User::query()->find($id)->delete();
    }

    public function render()
    {
        $users = User::with('roles')->orderByDesc('id')->paginate(20);
        return view('livewire.admin.users.index-livewire', compact('users'));
    }
}
