<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 min-h-screen flex flex-col">
        {{-- Command bar (top of content) --}}
        @include('components.commandbar')

        <div class="p-6 flex-1 w-full">
            @hasSection('header')
                <div class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">@yield('header')</h1>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide elements with data-auto-hide (ms)
            document.querySelectorAll('[data-auto-hide]').forEach(function (el) {
                var ms = parseInt(el.getAttribute('data-auto-hide')) || 2000;
                setTimeout(function () { el.style.display = 'none'; }, ms);
            });

            // Modal open/close helpers (listen for CustomEvent 'open-modal' and 'close-modal')
            window.addEventListener('open-modal', function (e) {
                var name = e && e.detail ? e.detail : null;
                if (!name) return;
                var modal = document.querySelector('[data-modal-name="' + name + '"]');
                if (!modal) return;
                document.body.classList.add('overflow-y-hidden');
                if (modal.getAttribute('data-focusable') === 'true') {
                    setTimeout(function () {
                        var selector = "a, button, input:not([type='hidden']), textarea, select, details, [tabindex]:not([tabindex='-1'])";
                        var focusables = Array.from(modal.querySelectorAll(selector)).filter(function (i) { return !i.hasAttribute('disabled'); });
                        if (focusables.length) focusables[0].focus();
                    }, 100);
                }
            });

            window.addEventListener('close-modal', function (e) {
                document.body.classList.remove('overflow-y-hidden');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
