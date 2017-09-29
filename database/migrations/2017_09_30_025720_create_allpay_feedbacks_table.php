<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllpayFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allpay_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('allpay_id')->unsigned();
            $table->string('MerchantID',10)->nullable();
            $table->string('MerchantTradeNo',20)->nullable();
            $table->string('StoreID',20)->nullable();
            $table->integer('RtnCode');
            $table->string('RtnMsg',200);

            $table->string('TradeNo',20);
            $table->integer('TradeAmt');
            $table->integer('PayAmt');
            $table->integer('RedeemAmt');
            $table->string('PaymentDate',20);
            $table->string('PaymentType',20);
            $table->integer('PaymentTypeChargeFee');
            $table->string('TradeDate',20);

            $table->string('PeriodType',1);
            $table->integer('Frequency');
            $table->integer('ExecTimes');
            $table->integer('Amount');
            $table->integer('Gwsr');
            $table->string('ProcessDate',20);
            $table->string('AuthCode',6);
            $table->integer('FirstAuthAmount');
            $table->integer('TotalSuccessTimes');
            $table->integer('SimulatePaid');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allpay_feedbacks');
    }
}
