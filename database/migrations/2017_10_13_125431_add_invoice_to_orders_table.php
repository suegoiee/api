<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('use_invoice')->default(0);
            $table->integer('invoice_type')->default(0);
            $table->string('invoice_name')->nullable();
            $table->string('invoice_phone')->nullable();
            $table->string('invoice_address')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_head')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('use_invoice');
            $table->dropColumn('invoice_type');
            $table->dropColumn('invoice_name');
            $table->dropColumn('invoice_phone');
            $table->dropColumn('invoice_address');
            $table->dropColumn('invoice_number');
            $table->dropColumn('invoice_head');
        });
    }
}
