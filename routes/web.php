<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Redirect /products to admin inventory products
    Route::get('/products', function () {
        return redirect()->route('admin.inventory.products.index');
    })->name('products.index');
});

// Manufacturing routes — allow any authenticated user to view/create MOs
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('manufacturing/dashboard', [\App\Http\Controllers\Admin\ManufacturingDashboardController::class, 'index'])->name('manufacturing.dashboard');
        Route::get('manufacturing/dashboard/trends-json', [\App\Http\Controllers\Admin\ManufacturingDashboardController::class, 'trendsJson'])->name('manufacturing.dashboard.trends-json');
        Route::get('manufacturing', [\App\Http\Controllers\Admin\ManufacturingController::class, 'index'])->name('manufacturing.index');
        Route::get('manufacturing/create', [\App\Http\Controllers\Admin\ManufacturingController::class, 'create'])->name('manufacturing.create');
        Route::get('manufacturing/{manufacturing}', [\App\Http\Controllers\Admin\ManufacturingController::class, 'show'])->name('manufacturing.show');
        Route::get('manufacturing/bom/{bomId}/details', [\App\Http\Controllers\Admin\ManufacturingController::class, 'getBomDetails'])->name('manufacturing.bom.details');
        Route::post('manufacturing', [\App\Http\Controllers\Admin\ManufacturingController::class, 'store'])->name('manufacturing.store');
        Route::patch('manufacturing/{manufacturing}/status', [\App\Http\Controllers\Admin\ManufacturingController::class, 'updateStatus'])->name('manufacturing.updateStatus');
        Route::delete('manufacturing/{manufacturing}', [\App\Http\Controllers\Admin\ManufacturingController::class, 'destroy'])->name('manufacturing.destroy');
    });

// Admin area (authenticated users)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        // Admin product routes
        Route::get('products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
        Route::post('products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
        // BoM resource
        Route::resource('bom', \App\Http\Controllers\Admin\BomController::class);
        // Inventory module
        Route::prefix('inventory')->name('inventory.')->group(function(){
            Route::get('/', [\App\Http\Controllers\Admin\InventoryDashboardController::class, 'index'])->name('dashboard');
            Route::get('dashboard/movements-json', [\App\Http\Controllers\Admin\InventoryDashboardController::class, 'movementsJson'])->name('dashboard.movements-json');

            // Products
            Route::get('products', [\App\Http\Controllers\Admin\InventoryProductController::class, 'index'])->name('products.index');
            Route::get('products/create', [\App\Http\Controllers\Admin\InventoryProductController::class, 'create'])->name('products.create');
            Route::post('products', [\App\Http\Controllers\Admin\InventoryProductController::class, 'store'])->name('products.store');
            Route::get('products/{product}/edit', [\App\Http\Controllers\Admin\InventoryProductController::class, 'edit'])->name('products.edit');
            Route::put('products/{product}', [\App\Http\Controllers\Admin\InventoryProductController::class, 'update'])->name('products.update');
            Route::delete('products/{product}', [\App\Http\Controllers\Admin\InventoryProductController::class, 'destroy'])->name('products.destroy');

            // Movements
            Route::get('movements', [\App\Http\Controllers\Admin\StockMovementController::class, 'index'])->name('movements.index');

            // Receipts
            Route::get('receipts', [\App\Http\Controllers\Admin\ReceiptController::class, 'index'])->name('receipts.index');
            Route::get('receipts/create', [\App\Http\Controllers\Admin\ReceiptController::class, 'create'])->name('receipts.create');
            Route::post('receipts', [\App\Http\Controllers\Admin\ReceiptController::class, 'store'])->name('receipts.store');

            // Deliveries
            Route::get('deliveries', [\App\Http\Controllers\Admin\DeliveryController::class, 'index'])->name('deliveries.index');
            Route::get('deliveries/create', [\App\Http\Controllers\Admin\DeliveryController::class, 'create'])->name('deliveries.create');
            Route::post('deliveries', [\App\Http\Controllers\Admin\DeliveryController::class, 'store'])->name('deliveries.store');

            // Transfers
            Route::get('transfers', [\App\Http\Controllers\Admin\TransferController::class, 'index'])->name('transfers.index');
            Route::get('transfers/create', [\App\Http\Controllers\Admin\TransferController::class, 'create'])->name('transfers.create');
            Route::post('transfers', [\App\Http\Controllers\Admin\TransferController::class, 'store'])->name('transfers.store');

            // Adjustments
            Route::get('adjustments', [\App\Http\Controllers\Admin\AdjustmentController::class, 'index'])->name('adjustments.index');
            Route::get('adjustments/create', [\App\Http\Controllers\Admin\AdjustmentController::class, 'create'])->name('adjustments.create');
            Route::post('adjustments', [\App\Http\Controllers\Admin\AdjustmentController::class, 'store'])->name('adjustments.store');
        });
    });

// Purchase module routes
Route::prefix('purchase')
    ->name('purchase.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Purchase\PurchaseDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/trends-json', [\App\Http\Controllers\Purchase\PurchaseDashboardController::class, 'trendsJson'])->name('dashboard.trends-json');
        
        // Purchase Orders
        Route::get('orders', [\App\Http\Controllers\Purchase\PurchaseOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [\App\Http\Controllers\Purchase\PurchaseOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [\App\Http\Controllers\Purchase\PurchaseOrderController::class, 'store'])->name('orders.store');
        Route::put('orders/{id}', [\App\Http\Controllers\Purchase\PurchaseOrderController::class, 'update'])->name('orders.update');
        Route::get('orders/{id}/edit', [\App\Http\Controllers\Purchase\PurchaseOrderController::class, 'edit'])->name('orders.edit');
        
        // Vendors
        Route::get('vendors', [\App\Http\Controllers\Purchase\VendorController::class, 'index'])->name('vendors.index');
        Route::get('vendors/create', [\App\Http\Controllers\Purchase\VendorController::class, 'create'])->name('vendors.create');
        Route::post('vendors', [\App\Http\Controllers\Purchase\VendorController::class, 'store'])->name('vendors.store');
        Route::put('vendors/{id}', [\App\Http\Controllers\Purchase\VendorController::class, 'update'])->name('vendors.update');
        Route::get('vendors/{id}/edit', [\App\Http\Controllers\Purchase\VendorController::class, 'edit'])->name('vendors.edit');
    });

// Sales module routes
Route::prefix('sales')
    ->name('sales.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Sales\SalesDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/trends-json', [\App\Http\Controllers\Sales\SalesDashboardController::class, 'trendsJson'])->name('dashboard.trends-json');
        
        // Sales Orders
        Route::get('orders', [\App\Http\Controllers\Sales\SalesOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [\App\Http\Controllers\Sales\SalesOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [\App\Http\Controllers\Sales\SalesOrderController::class, 'store'])->name('orders.store');
        Route::put('orders/{id}', [\App\Http\Controllers\Sales\SalesOrderController::class, 'update'])->name('orders.update');
        Route::get('orders/{id}/edit', [\App\Http\Controllers\Sales\SalesOrderController::class, 'edit'])->name('orders.edit');
        
        // Customers
        Route::get('customers', [\App\Http\Controllers\Sales\CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/create', [\App\Http\Controllers\Sales\CustomerController::class, 'create'])->name('customers.create');
        Route::post('customers', [\App\Http\Controllers\Sales\CustomerController::class, 'store'])->name('customers.store');
        Route::put('customers/{id}', [\App\Http\Controllers\Sales\CustomerController::class, 'update'])->name('customers.update');
        Route::get('customers/{id}/edit', [\App\Http\Controllers\Sales\CustomerController::class, 'edit'])->name('customers.edit');
    });

// Invoicing module routes
Route::prefix('invoicing')
    ->name('invoicing.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Invoicing\InvoiceDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/trends-json', [\App\Http\Controllers\Invoicing\InvoiceDashboardController::class, 'trendsJson'])->name('dashboard.trends-json');
        
        // Invoices
        Route::get('invoices', [\App\Http\Controllers\Invoicing\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/create', [\App\Http\Controllers\Invoicing\InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('invoices', [\App\Http\Controllers\Invoicing\InvoiceController::class, 'store'])->name('invoices.store');
        Route::put('invoices/{id}', [\App\Http\Controllers\Invoicing\InvoiceController::class, 'update'])->name('invoices.update');
        Route::get('invoices/{id}/edit', [\App\Http\Controllers\Invoicing\InvoiceController::class, 'edit'])->name('invoices.edit');
        
        // Payments
        Route::get('payments', [\App\Http\Controllers\Invoicing\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/create', [\App\Http\Controllers\Invoicing\PaymentController::class, 'create'])->name('payments.create');
        Route::get('payments/{id}', [\App\Http\Controllers\Invoicing\PaymentController::class, 'show'])->name('payments.show');
        Route::get('payments/{id}/edit', [\App\Http\Controllers\Invoicing\PaymentController::class, 'edit'])->name('payments.edit');
        Route::post('payments', [\App\Http\Controllers\Invoicing\PaymentController::class, 'store'])->name('payments.store');
        Route::put('payments/{id}', [\App\Http\Controllers\Invoicing\PaymentController::class, 'update'])->name('payments.update');
        Route::delete('payments/{id}', [\App\Http\Controllers\Invoicing\PaymentController::class, 'destroy'])->name('payments.destroy');
    });

// Employees module routes
Route::middleware(['auth'])->group(function () {
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);

    Route::post('employees/{employee}/documents', [\App\Http\Controllers\EmployeeDocumentController::class, 'store'])
        ->name('employees.documents.store');
    Route::delete('employees/{employee}/documents/{document}', [\App\Http\Controllers\EmployeeDocumentController::class, 'destroy'])
        ->name('employees.documents.destroy');
});

// Import routes
Route::prefix('import')->name('import.')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\ImportController::class, 'showUploadForm'])->name('show');
    Route::post('/', [\App\Http\Controllers\ImportController::class, 'processCsv'])->name('process');
});

// Settings routes
Route::prefix('settings')->name('settings.')->middleware(['auth'])->group(function () {
    Route::get('general', [\App\Http\Controllers\SettingsController::class, 'general'])->name('general');
    Route::post('general', [\App\Http\Controllers\SettingsController::class, 'updateGeneral'])->name('general.update');
    Route::get('currency', [\App\Http\Controllers\SettingsController::class, 'currency'])->name('currency');
    Route::post('currency', [\App\Http\Controllers\SettingsController::class, 'updateCurrency'])->name('currency.update');
});

require __DIR__.'/auth.php';
