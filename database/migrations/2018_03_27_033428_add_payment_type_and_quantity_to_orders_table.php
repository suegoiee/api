<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentTypeAndQuantityToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('paymentType')->default('credit');
        });
        Schema::table('order_product', function (Blueprint $table) {
            $table->integer('unit_price')->default(0);
            $table->integer('quantity')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('unit_price');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('paymentType');
        });
    }
}
