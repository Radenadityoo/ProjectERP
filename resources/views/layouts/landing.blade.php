<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ERP') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col">
        <nav class="w-full border-b bg-white dark:bg-gray-900 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-erp flex items-center justify-center text-white">E</div>
                            <span class="font-semibold text-lg">{{ config('app.name', 'ERP') }}</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-medium bg-erp text-white">Register</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md text-sm font-medium bg-erp text-white">Dashboard</a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1">
            @yield('content')
        </main>
    </div>
</body>
</html>
