<div class="h-[72px] border-b bg-white dark:bg-gray-800 flex items-center px-6">
    <div class="flex items-center gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $commandbar['title'] ?? 'Dashboard' }}</h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">Showing {{ number_format($commandbar['count'] ?? 0) }} records</div>
        </div>
    </div>

    <div class="flex-1 flex justify-center">
        <div class="w-full max-w-2xl flex items-center gap-3">
            @if(isset($commandbar['searchParam']))
            <form method="GET" class="flex-1 flex gap-3">
                <!-- Preserve other query params -->
                @foreach(request()->query() as $key => $value)
                    @if($key !== $commandbar['searchParam'])
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                    @endif
                @endforeach
                
                <label for="cmd-search" class="sr-only">Search</label>
                <input id="cmd-search" type="search" name="{{ $commandbar['searchParam'] }}" value="{{ request($commandbar['searchParam']) }}" placeholder="Search records..." class="w-full py-2 px-3 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-erp" />
                <button type="submit" class="px-4 py-2 bg-erp text-white rounded font-medium hover:bg-opacity-90 transition-all">Search</button>
            </form>
            @else
            <label for="cmd-search" class="sr-only">Search</label>
            <input id="cmd-search" type="search" placeholder="Search records..." class="w-full py-2 px-3 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
            @endif

            @if(isset($commandbar['showViewSwitch']) && $commandbar['showViewSwitch'])
                <select id="view-select" class="py-2 px-3 rounded border bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100">
                    <option value="table">Table</option>
                    <option value="cards">Cards</option>
                </select>
            @endif
        </div>
    </div>

</div>

@if(isset($commandbar['showViewSwitch']) && $commandbar['showViewSwitch'])
    @push('scripts')
    <script>
        (function() {
            const viewSelect = document.getElementById('view-select');
            const storageKey = '{{ $commandbar["viewStorageKey"] ?? "viewMode" }}';
            const viewMode = localStorage.getItem(storageKey) || 'table';
            
            // Set initial select value
            viewSelect.value = viewMode;
            
            // Handle view change
            viewSelect.addEventListener('change', function() {
                localStorage.setItem(storageKey, this.value);
                location.reload();
            });
        })();
    </script>
    @endpush
@endif
