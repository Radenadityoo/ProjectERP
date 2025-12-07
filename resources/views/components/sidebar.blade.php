<aside class="flex-shrink-0 w-[220px] min-h-screen bg-[#F7F8F7] dark:bg-gray-900 text-[#1A1A1A] dark:text-gray-100">
    <div class="px-3 py-4">
        <a href="{{ url('/') }}" class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded bg-erp flex items-center justify-center" style="background:#5A8E74;">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect></svg>
            </div>
            <span class="text-lg font-semibold">{{ config('app.name','ERP') }}</span>
        </a>

        {{-- Profile / quick area --}}
        @auth
            <div class="mb-2">
                <a href="{{ route('profile.edit') }}" class="block bg-white dark:bg-gray-800 rounded p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm">{{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}</div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endauth

        {{-- Navigation sections --}}
        <div class="mt-2">
            @php
                // manufacturing & inventory actives
                $active = request()->routeIs('admin.manufacturing.*');
                $activeP = request()->routeIs('products.*');
                $activeBom = request()->routeIs('admin.bom.*');

                $activeInvDashboard = request()->routeIs('admin.inventory.dashboard');
                $activeInvProducts = request()->routeIs('admin.inventory.products.*');
                $activeInvMovements = request()->routeIs('admin.inventory.movements.*');
                $activeInvReceipts = request()->routeIs('admin.inventory.receipts.*');
                $activeInvDeliveries = request()->routeIs('admin.inventory.deliveries.*');
                $activeInvTransfers = request()->routeIs('admin.inventory.transfers.*');
                $activeInvAdjustments = request()->routeIs('admin.inventory.adjustments.*');

                // purchase actives
                $activePurchaseDashboard = request()->routeIs('purchase.dashboard');
                $activePurchaseOrders = request()->routeIs('purchase.orders.*');
                $activePurchaseVendors = request()->routeIs('purchase.vendors.*');

                // sales actives
                $activeSalesDashboard = request()->routeIs('sales.dashboard');
                $activeSalesOrders = request()->routeIs('sales.orders.*');
                $activeSalesCustomers = request()->routeIs('sales.customers.*');
            @endphp

            <div class="module-section mb-2" data-section="modules-parent">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded text-left text-[11px] uppercase text-[#8A8A8A]" data-toggle>
                    <span>Modules</span>
                    <svg class="w-4 h-4 transition-transform" data-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>

                <div class="module-contents px-1 mt-2" data-content>
                    <div class="module-section mb-2" data-section="manufacturing">
                        <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded text-left text-[11px] uppercase text-[#8A8A8A]" data-toggle>
                            <span>Manufacturing</span>
                            <svg class="w-4 h-4 transition-transform" data-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>

                        <nav class="space-y-2 px-1 mt-2 module-contents-inner" data-content>
                            <a href="{{ route('admin.manufacturing.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $active ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $active ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><path d="M3 14h7v7H3z"/></svg>
                                </span>
                                <span class="text-sm {{ $active ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Manufacturing Orders</span>
                            </a>

                            <a href="{{ route('products.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeP ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeP ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M20 7H4"/><path d="M4 17h16"/></svg>
                                </span>
                                <span class="text-sm {{ $activeP ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Products</span>
                            </a>

                            <a href="{{ route('admin.bom.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeBom ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeBom ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/></svg>
                                </span>
                                <span class="text-sm {{ $activeBom ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Bill of Materials</span>
                            </a>
                        </nav>
                    </div>

                    <div class="module-section mb-2" data-section="inventory">
                        <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded text-left text-[11px] uppercase text-[#8A8A8A]" data-toggle>
                            <span>Inventory</span>
                            <svg class="w-4 h-4 transition-transform" data-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>

                        <nav class="space-y-2 px-1 mt-2 module-contents-inner" data-content>
                            <a href="{{ route('admin.inventory.dashboard') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeInvDashboard ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeInvDashboard ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M3 13h8V3H3z"/><path d="M13 21h8V11h-8z"/></svg>
                                </span>
                                <span class="text-sm {{ $activeInvDashboard ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Inventory Dashboard</span>
                            </a>

                            <a href="{{ route('admin.inventory.products.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeInvProducts ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeInvProducts ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M20 7H4"/><path d="M4 17h16"/></svg>
                                </span>
                                <span class="text-sm {{ $activeInvProducts ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Products</span>
                            </a>

                            <a href="{{ route('admin.inventory.movements.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeInvMovements ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeInvMovements ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/></svg>
                                </span>
                                <span class="text-sm {{ $activeInvMovements ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Movements</span>
                            </a>

                            <a href="{{ route('admin.inventory.receipts.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeInvReceipts ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeInvReceipts ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M21 15V6a2 2 0 0 0-2-2H7L3 6v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2z"/></svg>
                                </span>
                                <span class="text-sm {{ $activeInvReceipts ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Receipts</span>
                            </a>

                            <a href="{{ route('admin.inventory.deliveries.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeInvDeliveries ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeInvDeliveries ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M3 7h18"/><path d="M5 21h14l-1-7H6l-1 7z"/></svg>
                                </span>
                                <span class="text-sm {{ $activeInvDeliveries ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Deliveries</span>
                            </a>

                            <a href="{{ route('admin.inventory.transfers.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeInvTransfers ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeInvTransfers ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M7 17l-4-4 4-4"/><path d="M17 7l4 4-4 4"/></svg>
                                </span>
                                <span class="text-sm {{ $activeInvTransfers ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Transfers</span>
                            </a>

                            <a href="{{ route('admin.inventory.adjustments.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeInvAdjustments ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeInvAdjustments ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M12 2v20"/><path d="M2 12h20"/></svg>
                                </span>
                                <span class="text-sm {{ $activeInvAdjustments ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Adjustments</span>
                            </a>
                        </nav>
                    </div>

                    <div class="module-section mb-2" data-section="purchase">
                        <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded text-left text-[11px] uppercase text-[#8A8A8A]" data-toggle>
                            <span>Purchase</span>
                            <svg class="w-4 h-4 transition-transform" data-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>

                        <nav class="space-y-2 px-1 mt-2 module-contents-inner" data-content>
                            <a href="{{ route('purchase.dashboard') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activePurchaseDashboard ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activePurchaseDashboard ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M3 13h8V3H3z"/><path d="M13 21h8V11h-8z"/></svg>
                                </span>
                                <span class="text-sm {{ $activePurchaseDashboard ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Purchase Dashboard</span>
                            </a>

                            <a href="{{ route('purchase.orders.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activePurchaseOrders ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activePurchaseOrders ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 13h6"/><path d="M9 17h6"/></svg>
                                </span>
                                <span class="text-sm {{ $activePurchaseOrders ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Purchase Orders</span>
                            </a>

                            <a href="{{ route('purchase.vendors.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activePurchaseVendors ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activePurchaseVendors ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6M23 11h-6"/></svg>
                                </span>
                                <span class="text-sm {{ $activePurchaseVendors ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Vendors</span>
                            </a>
                        </nav>
                    </div>

                    <div class="module-section mb-2" data-section="sales">
                        <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded text-left text-[11px] uppercase text-[#8A8A8A]" data-toggle>
                            <span>Sales</span>
                            <svg class="w-4 h-4 transition-transform" data-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>

                        <nav class="space-y-2 px-1 mt-2 module-contents-inner" data-content>
                            <a href="{{ route('sales.dashboard') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeSalesDashboard ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeSalesDashboard ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M3 13h8V3H3z"/><path d="M13 21h8V11h-8z"/></svg>
                                </span>
                                <span class="text-sm {{ $activeSalesDashboard ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Sales Dashboard</span>
                            </a>

                            <a href="{{ route('sales.orders.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeSalesOrders ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeSalesOrders ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 13h6"/><path d="M9 17h6"/></svg>
                                </span>
                                <span class="text-sm {{ $activeSalesOrders ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Sales Orders</span>
                            </a>

                            <a href="{{ route('sales.customers.index') }}" class="flex items-center gap-3 h-[40px] px-3 rounded-lg {{ $activeSalesCustomers ? 'bg-[#E4EFE9] dark:bg-[#163a2a]' : '' }}">
                                <span class="inline-flex items-center justify-center w-[18px] h-[18px] {{ $activeSalesCustomers ? 'text-erp' : 'text-gray-400 dark:text-gray-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-[18px] h-[18px]"><path d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm6 3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/></svg>
                                </span>
                                <span class="text-sm {{ $activeSalesCustomers ? 'text-erp' : 'text-[#1A1A1A] dark:text-gray-100' }}">Customers</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- Theme toggle + Logout --}}
        <div class="mt-4 px-3 space-y-3">
            <div class="flex items-center justify-between" style="height:40px; padding:6px 12px; border-radius:8px;">
                <div class="flex items-center gap-3">
                    <button id="theme-toggle" type="button" class="flex items-center gap-2 text-sm" aria-label="Toggle theme">
                        <span id="theme-icon">
                            <!-- default: sun -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px; color:#D97706;"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                        </span>
                        <span class="text-sm">Theme</span>
                    </button>
                </div>
            </div>

            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3" style="height:40px; padding:12px; border-radius:8px;">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            function setTheme(theme){
                const root = document.documentElement;
                if(theme === 'dark'){
                    root.classList.add('dark');
                    localStorage.setItem('theme','dark');
                    themeIcon.innerHTML = `\
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;color:#F3F4F6"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>\
                    `;
                } else {
                    root.classList.remove('dark');
                    localStorage.setItem('theme','light');
                    themeIcon.innerHTML = `\
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;color:#D97706"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>\
                    `;
                }
            }

            // init
            const saved = localStorage.getItem('theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            setTheme(saved);

            themeToggle.addEventListener('click', function(){
                const current = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                setTheme(current === 'dark' ? 'light' : 'dark');
            });
        })();
    </script>
    <script>
        // Sidebar module collapse/expand with smooth max-height transitions
        (function(){
            const storageKey = 'sidebar_sections_open';
            function load() {
                try { return JSON.parse(localStorage.getItem(storageKey) || '{}'); } catch(e){ return {}; }
            }
            function save(state){ localStorage.setItem(storageKey, JSON.stringify(state)); }

            const state = load();

            // helper to animate open/close using max-height
            function setAnimatedOpen(contentEl, open){
                if(!contentEl) return;
                contentEl.style.overflow = 'hidden';
                contentEl.style.transition = 'max-height 260ms cubic-bezier(.2,.9,.2,1)';
                if(open){
                    // set to measured height
                    const prev = contentEl.style.maxHeight;
                    contentEl.style.maxHeight = contentEl.scrollHeight + 'px';
                    // after transition, keep its height to the measured value
                    window.setTimeout(()=>{
                        contentEl.style.maxHeight = contentEl.scrollHeight + 'px';
                    }, 300);
                } else {
                    contentEl.style.maxHeight = '0px';
                }
            }

            // initialize each module-section (works for parent and inner sections)
            const sections = Array.from(document.querySelectorAll('.module-section'));
            document.querySelectorAll('.module-section').forEach(section=>{
                const name = section.dataset.section;
                const toggle = section.querySelector('[data-toggle]');
                const content = section.querySelector('[data-content]');
                const chevron = section.querySelector('[data-chevron]');

                // if no content (edge cases) skip
                if(!toggle || !content) return;

                // initial: open if saved OR if any active link inside
                function hasActiveDescendant(root){
                    // prefer the simple class selector first
                    if(root.querySelector('.text-erp')) return true;
                    // some Tailwind generated classes have special characters (e.g. bg-[#E4EFE9])
                    // which are invalid in querySelector without escaping. Fall back to scanning.
                    const nodes = root.querySelectorAll('*');
                    for(let i=0;i<nodes.length;i++){
                        const el = nodes[i];
                        if(!el.classList) continue;
                        if(el.classList.contains('bg-[#E4EFE9]')) return true;
                    }
                    return false;
                }
                    const hasActive = hasActiveDescendant(content);
                    const initialOpen = (state[name] === undefined) ? !!hasActive : !!state[name];

                // prepare styles
                content.style.maxHeight = initialOpen ? content.scrollHeight + 'px' : '0px';
                content.style.overflow = 'hidden';
                content.style.transition = 'max-height 260ms cubic-bezier(.2,.9,.2,1)';
                if(chevron){ chevron.style.transform = initialOpen ? 'rotate(180deg)' : ''; }

                // save initial
                state[name] = !!initialOpen;

                toggle.addEventListener('click', ()=>{
                    const open = !state[name];
                    setAnimatedOpen(content, open);
                    if(chevron) chevron.style.transform = open ? 'rotate(180deg)' : '';
                    state[name] = !!open; save(state);
                    // when a nested section toggles, defer ancestor adjust until transition ends
                    window.setTimeout(()=> adjustAncestorHeights(section), 340);
                });
            });

                // After preparing handlers, ensure parents have correct heights if children are open.
                // Walk from deepest to root so parent maxHeights include opened children.
                sections.reverse().forEach(section=>{
                    if(!section.dataset || !state[section.dataset.section]) return;
                    const content = section.querySelector('[data-content]');
                    if(content) content.style.maxHeight = content.scrollHeight + 'px';
                    // also adjust parents up the chain
                    adjustAncestorHeights(section);
                });


                // helper: when a section changes, make sure any ancestor content grows to fit
                function adjustAncestorHeights(startSection){
                    let parent = startSection.parentElement;
                    while(parent){
                        const ancestorSection = parent.closest('.module-section');
                        if(!ancestorSection) break;
                        const ancestorName = ancestorSection.dataset.section;
                        const ancestorContent = ancestorSection.querySelector('[data-content]');
                        if(ancestorContent && state[ancestorName]){
                            // set to measured height to accommodate inner open/close
                            ancestorContent.style.maxHeight = ancestorContent.scrollHeight + 'px';
                        }
                        parent = ancestorSection.parentElement;
                    }
                }

            // persist initial state
            save(state);
        })();
    </script>
</aside>
