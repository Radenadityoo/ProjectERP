<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bom_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_id');
            $table->unsignedBigInteger('component_product_id')->nullable();
            $table->decimal('qty', 12, 4)->default(1);
            $table->decimal('unit_cost', 14, 4)->default(0);
            $table->decimal('subtotal', 14, 4)->default(0);
            $table->timestamps();

            $table->foreign('bom_id')->references('id')->on('bom_headers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bom_components');
    }
};
