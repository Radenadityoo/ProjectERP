@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Products</h1>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        {{-- Add Product card first --}}
        <div class="">
            @include('components.products.add-product-card')
        </div>

        {{-- Product items --}}
        @foreach($products as $p)
            <div>
                @include('components.products.product-card', ['product' => $p])
            </div>
        @endforeach
    </div>

@endsection
