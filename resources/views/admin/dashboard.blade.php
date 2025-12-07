@extends('layouts.admin')

@section('title', 'Admin — Dashboard')

@section('header', 'Overview')

@section('content')
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 border rounded">
            <h3 class="text-sm font-medium">Total Products</h3>
            <p class="text-2xl mt-2">--</p>
        </div>
        <div class="p-4 border rounded">
            <h3 class="text-sm font-medium">Active Users</h3>
            <p class="text-2xl mt-2">--</p>
        </div>
        <div class="p-4 border rounded">
            <h3 class="text-sm font-medium">Orders</h3>
            <p class="text-2xl mt-2">--</p>
        </div>
    </div>
@endsection
