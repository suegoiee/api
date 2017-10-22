<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcpayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecpays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('MerchantTradeNo',20);
            $table->timestamps();
        });
        Schema::create('ecpay_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ecpay_id')->unsigned();
            $table->string('MerchantID',10)->nullable();
            $table->string('MerchantTradeNo',20)->nullable();
            $table->string('StoreID',20)->nullable();
            $table->integer('RtnCode')->default(0);
            $table->string('RtnMsg',200)->nullable();

            $table->string('TradeNo',20)->nullable();
            $table->integer('TradeAmt')->default(0);
            $table->integer('PayAmt')->default(0);
            $table->integer('RedeemAmt')->default(0);
            $table->string('PaymentDate',20)->nullable();
            $table->string('PaymentType',20)->nullable();
            $table->integer('PaymentTypeChargeFee')->default(0);
            $table->string('TradeDate',20)->nullable();

            $table->string('PeriodType',1)->nullable();
            $table->integer('Frequency')->default(0);
            $table->integer('ExecTimes')->default(0);
            $table->integer('Amount')->default(0);
            $table->integer('Gwsr')->default(0);
            $table->string('ProcessDate',20)->nullable();
            $table->string('AuthCode',6)->nullable();
            $table->integer('FirstAuthAmount')->default(0);
            $table->integer('TotalSuccessTimes')->default(0);
            $table->integer('SimulatePaid')->default(0);
            $table->string('CustomField1',50)->nullable();
            $table->string('CustomField2',50)->nullable();
            $table->string('CustomField3',50)->nullable();
            $table->string('CustomField4',50)->nullable();
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
        Schema::dropIfExists('ecpay_feedbacks');
        Schema::dropIfExists('ecpays');
    }
}
