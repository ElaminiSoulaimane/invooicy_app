<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_invoice_number_to_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceNumberToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_number')->unique()->after('id'); // Add the invoice_number column
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_number'); // Remove the column if rolling back
        });
    }
}
