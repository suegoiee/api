<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultPayamtToAllpayFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('allpay_feedbacks', function (Blueprint $table) {
            $table->integer('RtnCode')->default(0)->change();
            $table->string('RtnMsg',200)->nullable()->change();

            $table->string('TradeNo',20)->nullable()->change();
            $table->integer('TradeAmt')->default(0)->change();
            $table->integer('PayAmt')->default(0)->change();
            $table->integer('RedeemAmt')->default(0)->change();
            $table->string('PaymentDate',20)->nullable()->change();
            $table->string('PaymentType',20)->nullable()->change();
            $table->integer('PaymentTypeChargeFee')->default(0)->change();
            $table->string('TradeDate',20)->nullable()->change();

            $table->string('PeriodType',1)->nullable()->change();
            $table->integer('Frequency')->default(0)->change();
            $table->integer('ExecTimes')->default(0)->change();
            $table->integer('Amount')->default(0)->change();
            $table->integer('Gwsr')->default(0)->change();
            $table->string('ProcessDate',20)->nullable()->change();
            $table->string('AuthCode',6)->nullable()->change();
            $table->integer('FirstAuthAmount')->default(0)->change();
            $table->integer('TotalSuccessTimes')->default(0)->change();
            $table->integer('SimulatePaid')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allpay_feedbacks', function (Blueprint $table) {
            $table->integer('RtnCode')->change();
            $table->string('RtnMsg',200)->change();

            $table->string('TradeNo',20)->change();
            $table->integer('TradeAmt')->change();
            $table->integer('PayAmt')->change();
            $table->integer('RedeemAmt')->change();
            $table->string('PaymentDate',20)->change();
            $table->string('PaymentType',20)->change();
            $table->integer('PaymentTypeChargeFee')->change();
            $table->string('TradeDate',20)->change();

            $table->string('PeriodType',1)->change();
            $table->integer('Frequency')->change();
            $table->integer('ExecTimes')->change();
            $table->integer('Amount')->change();
            $table->integer('Gwsr')->change();
            $table->string('ProcessDate',20)->change();
            $table->string('AuthCode',6)->change();
            $table->integer('FirstAuthAmount')->change();
            $table->integer('TotalSuccessTimes')->change();
            $table->integer('SimulatePaid')->change();
            
        });
    }
}
