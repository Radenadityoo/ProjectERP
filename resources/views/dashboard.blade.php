@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
                <div class="mt-4 text-gray-900">{{ __("You're logged in!") }}</div>
            </div>
        </div>
    </div>
@endsection
