@extends('layouts.admin.admin')

@section('content')
    <div class="content-wrapper">
        @livewire('admin.roles.index-livewire')
    </div>
    <!-- Create Role Modal -->
    <div id="CreateRoleModal" class="modal fade" tabindex="-1" aria-labelledby="CreateRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('admin.roles.create-livewire')
                </div>
            </div>
        </div>
    </div>

    <div id="editRoleModal" class="modal fade" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('admin.roles.edit-livewire')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.addEventListener('closeModal', event => {
            $('#CreateRoleModal').modal('hide');
            $('#editRoleModal').modal('hide');
        });

        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('hideCreateModal', () => {
                let createModal = bootstrap.Modal.getInstance(document.getElementById('CreateRoleModal'));
                if (createModal) {
                    createModal.hide();
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop')?.remove();
                }
            });

            Livewire.on('hideEditModal', () => {
                let editModal = bootstrap.Modal.getInstance(document.getElementById('editRoleModal'));
                if (editModal) {
                    editModal.hide();
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop')?.remove();
                }
            });
        });
    </script>
@endsection

