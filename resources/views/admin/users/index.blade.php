@extends('layouts.admin.admin')

@section('content')
    <div class="content-wrapper">
       @livewire('admin.users.index-livewire')
    </div>
    <!-- Create User Modal -->
    <div id="createUserModal" class="modal fade" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('admin.users.create-livewire')
                </div>
            </div>
        </div>
    </div>

   <div>
       @livewire('admin.users.edit-livewire')
   </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('hideCreateModal', () => {
                let createModal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
                if (createModal) {
                    createModal.hide();
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop')?.remove();
                }
            });

            Livewire.on('hideEditModal', () => {
                let createModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                if (createModal) {
                    createModal.hide();
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop')?.remove();
                }
            });
        });
    </script>
@endsection
