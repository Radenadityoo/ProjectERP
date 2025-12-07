@extends('layouts.landing')

@section('content')
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Manage your operations effortlessly</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">A minimal, modern admin experience for small teams. Track products, manufacturing orders, and more — all in one place.</p>

            <div class="mt-8 flex items-center justify-center gap-3">
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-md border border-gray-300 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">Login</a>
                <a href="{{ route('register') }}" class="px-6 py-3 rounded-md bg-erp text-white text-sm font-medium">Register</a>
            </div>
        </div>
    </section>

    <section class="pb-20">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-lg border border-gray-100 bg-white dark:bg-gray-800">
                    <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">💡</div>
                    <h3 class="mt-4 font-semibold">Simple setup</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Get started quickly with sensible defaults and a small footprint.</p>
                </div>

                <div class="p-6 rounded-lg border border-gray-100 bg-white dark:bg-gray-800">
                    <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">⚙️</div>
                    <h3 class="mt-4 font-semibold">Flexible workflows</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Customize processes for manufacturing, inventory, and more.</p>
                </div>

                <div class="p-6 rounded-lg border border-gray-100 bg-white dark:bg-gray-800">
                    <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">🔒</div>
                    <h3 class="mt-4 font-semibold">Secure by default</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Built with Laravel's auth and sensible defaults to protect your data.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
