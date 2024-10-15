<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change(); // Make user_id nullable
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change(); // Revert to NOT NULL if needed
        });
    }
}
