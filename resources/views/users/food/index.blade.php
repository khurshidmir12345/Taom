@extends('layouts.user.app')
@section('styles')
    <style>
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .randomize-btn.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .clock-icon {
            vertical-align: middle;
            margin-right: 5px;
        }

        .empty-card {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .food-placeholder-svg {
            margin: 0 auto;
            display: block;
            color: #999;
        }
    </style>
@endsection
@section('content')
    @livewire('user.randomize.food-randomize-livewire')
@endsection

