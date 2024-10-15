<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuyPriceToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add the buy_price column
            $table->decimal('buy_price', 10, 2)->after('price')->nullable()->default(0);
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Remove the buy_price column if the migration is rolled back
            $table->dropColumn('buy_price');
        });
    }
}
