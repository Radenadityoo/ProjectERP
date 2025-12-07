<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->boolean('track_inventory')->default(true);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('category')->nullable();
            $table->string('reference')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->string('company')->nullable();
            $table->text('description')->nullable();
            $table->text('internal_notes')->nullable();
            $table->decimal('quantity', 12, 4)->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
