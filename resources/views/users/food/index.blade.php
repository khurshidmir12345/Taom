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
    <style>
        .food-history-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 24px;
            max-width: 400px;
            width: 100%;
            margin: 20px auto;
            text-align: center;
            animation: fadeIn 0.3s ease-in-out;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a202c;
        }

        .card-message {
            font-size: 0.875rem;
            color: #4a5568;
            line-height: 1.5;
        }

        .card-close-btn {
            background: #3182ce;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .card-close-btn:hover {
            background: #2b6cb0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection
@section('content')
    @livewire('user.randomize.food-randomize-livewire')
@endsection

