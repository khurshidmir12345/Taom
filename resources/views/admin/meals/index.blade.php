@extends('layouts.admin.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        @livewire('admin.meals.index-livewire')
    </div>
@endsection
