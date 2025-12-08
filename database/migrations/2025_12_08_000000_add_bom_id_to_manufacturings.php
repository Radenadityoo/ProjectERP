<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('manufacturings', function (Blueprint $table) {
            $table->unsignedBigInteger('bom_id')->nullable()->after('product_id');
            $table->foreign('bom_id')->references('id')->on('bom_headers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('manufacturings', function (Blueprint $table) {
            $table->dropForeign(['bom_id']);
            $table->dropColumn('bom_id');
        });
    }
};
