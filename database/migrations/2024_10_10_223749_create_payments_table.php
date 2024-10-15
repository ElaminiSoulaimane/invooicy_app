<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamp('payment_date')->default(now());
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade'); // Link to the invoices table
            $table->decimal('amount', 10, 2);
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to the users table
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
