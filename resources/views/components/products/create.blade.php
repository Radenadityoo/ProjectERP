@extends('layouts.admin')

@section('title', 'New Product')

@section('header')
    New Product
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        <div class="mb-6">
            <div class="flex gap-4 bg-gray-100 p-4 rounded">
                <button type="button" class="px-4 py-2 border rounded bg-white dark:bg-gray-700">Update Quantity</button>
                <button type="button" class="px-4 py-2 border rounded bg-white dark:bg-gray-700">Replenish</button>
                <button type="button" class="px-4 py-2 border rounded bg-white dark:bg-gray-700">Print Label</button>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block mb-2">Product</label>
                <input name="name" class="w-full border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="Product name" />

                <div class="mt-4">
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="sale" class="mr-2" checked />
                        Sales
                    </label>
                </div>

                <div class="mt-4">
                    <h4 class="font-medium mb-2">General Information</h4>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center"><input type="radio" name="type" value="good" class="mr-2"> Product</label>
                        <label class="inline-flex items-center"><input type="radio" name="type" value="service" class="mr-2"> Service</label>
                        <label class="inline-flex items-center"><input type="radio" name="type" value="combo" class="mr-2"> Combo</label>
                    </div>

                    <div class="mt-4">
                        <label class="inline-flex items-center"><input type="checkbox" name="track_inventory" class="mr-2"> Track by Quantity</label>
                    </div>
                </div>

            </div>

            <div>
                    <div class="grid gap-3">
                    <div class="flex justify-between items-center">
                        <label>Sales Price</label>
                        <input name="price" class="w-32 border border-gray-300 dark:border-gray-600 rounded p-2 text-right bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="0.00" />
                    </div>
                    <div class="flex justify-between items-center">
                        <label>Cost</label>
                        <input name="cost" class="w-32 border border-gray-300 dark:border-gray-600 rounded p-2 text-right bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="0.00" />
                    </div>
                    <div class="flex justify-between items-center">
                        <label>Category</label>
                        <input name="category" class="w-32 border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                    </div>
                    <div class="flex justify-between items-center">
                        <label>Reference</label>
                        <input name="reference" class="w-32 border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                    </div>
                    <div class="flex justify-between items-center">
                        <label>Barcode</label>
                        <input name="barcode" class="w-32 border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="bg-erp text-white px-4 py-2 rounded">Save</button>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
@endsection
