<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->string('currency_code', 3)->default('IDR')->after('status');
            $table->decimal('exchange_rate', 18, 6)->default(1)->after('currency_code');
            $table->decimal('subtotal_base', 15, 2)->default(0)->after('subtotal');
            $table->decimal('tax_amount_base', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('total_base', 15, 2)->default(0)->after('total');
        });

        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->string('currency_code', 3)->default('IDR')->after('tax_rate');
            $table->decimal('exchange_rate', 18, 6)->default(1)->after('currency_code');
            $table->decimal('unit_price_base', 15, 2)->default(0)->after('unit_price');
            $table->decimal('subtotal_base', 15, 2)->default(0)->after('subtotal');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->decimal('exchange_rate', 18, 6)->default(1)->after('currency');
            $table->decimal('subtotal_base', 15, 2)->default(0)->after('subtotal');
            $table->decimal('tax_amount_base', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('total_base', 15, 2)->default(0)->after('total');
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->string('currency_code', 3)->default('IDR')->after('tax_rate');
            $table->decimal('exchange_rate', 18, 6)->default(1)->after('currency_code');
            $table->decimal('unit_price_base', 15, 2)->default(0)->after('unit_price');
            $table->decimal('subtotal_base', 15, 2)->default(0)->after('subtotal');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('currency_code', 3)->default('IDR')->after('status');
            $table->decimal('exchange_rate', 18, 6)->default(1)->after('currency_code');
            $table->decimal('subtotal_base', 15, 2)->default(0)->after('subtotal');
            $table->decimal('tax_amount_base', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('total_base', 15, 2)->default(0)->after('total');
            $table->decimal('amount_paid_base', 15, 2)->default(0)->after('amount_paid');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('currency_code', 3)->default('IDR')->after('tax_rate');
            $table->decimal('exchange_rate', 18, 6)->default(1)->after('currency_code');
            $table->decimal('unit_price_base', 15, 2)->default(0)->after('unit_price');
            $table->decimal('subtotal_base', 15, 2)->default(0)->after('subtotal');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('currency_code', 3)->default('IDR')->after('payment_method');
            $table->decimal('exchange_rate', 18, 6)->default(1)->after('currency_code');
            $table->decimal('amount_base', 15, 2)->default(0)->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'exchange_rate', 'subtotal_base', 'tax_amount_base', 'total_base']);
        });

        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'exchange_rate', 'unit_price_base', 'subtotal_base']);
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['exchange_rate', 'subtotal_base', 'tax_amount_base', 'total_base']);
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'exchange_rate', 'unit_price_base', 'subtotal_base']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'exchange_rate', 'subtotal_base', 'tax_amount_base', 'total_base', 'amount_paid_base']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'exchange_rate', 'unit_price_base', 'subtotal_base']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'exchange_rate', 'amount_base']);
        });
    }
};
