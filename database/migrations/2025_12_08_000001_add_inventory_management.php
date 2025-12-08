<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if description column doesn't exist before adding
        if (!Schema::hasColumn('products', 'description')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('description')->nullable()->after('quantity');
            });
        }

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('reference_type'); // 'manufacturing_order', 'purchase_order', 'sales_order', 'adjustment'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('quantity_in', 12, 2)->default(0);
            $table->decimal('quantity_out', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'description')) {
                $table->dropColumn('description');
            }
        });

        Schema::dropIfExists('inventory_movements');
    }
};
