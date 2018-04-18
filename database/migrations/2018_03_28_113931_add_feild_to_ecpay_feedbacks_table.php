<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeildToEcpayFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecpay_feedbacks', function (Blueprint $table) {
            $table->string('BankCode', 3)->default('');
            $table->string('vAccount', 16)->default('');
            $table->string('ExpireDate', 20)->default('');
            $table->string('PaymentNo', 14)->default('');
            $table->string('Barcode1', 20)->default('');
            $table->string('Barcode2', 20)->default('');
            $table->string('Barcode3', 20)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecpay_feedbacks', function (Blueprint $table) {
            $table->dropColumn('BankCode');
            $table->dropColumn('vAccount');
            $table->dropColumn('ExpireDate');
            $table->dropColumn('PaymentNo');
            $table->dropColumn('Barcode1');
            $table->dropColumn('Barcode2');
            $table->dropColumn('Barcode3');
        });
    }
}
