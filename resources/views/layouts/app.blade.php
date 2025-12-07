<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
</body>
<body class="min-h-screen flex bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 min-h-screen">
        @yield('content')
    </main>
</body>
</body>
</html>
