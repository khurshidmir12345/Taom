@extends('layouts.admin.admin')

@section('content')
    <div class="content-wrapper">
        @livewire('admin.permissions.index-livewire')
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('showPermissionModal', () => {
                let modal = new bootstrap.Modal(document.getElementById('permissionModal'));
                modal.show();
            });

            Livewire.on('hidePermissionModal', () => {
                let modal = bootstrap.Modal.getInstance(document.getElementById('permissionModal'));
                if (modal) modal.hide();
            });
        });

    </script>
@endsection
