<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capitals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('CustID',10)->default('');
            $table->string('AwardName',30)->default('');
            $table->integer('Points')->default(0);
            $table->string('VendorName',30)->default('');
            $table->integer('PayAmt')->default(0);
            $table->string('StatusCode',4)->default('');
            $table->text('Massage')->nullable();
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
        Schema::dropIfExists('capitals');
    }
}
