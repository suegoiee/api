<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReferrerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_referrer', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('referrer_id')->unsigned();
            $table->double('divided', 4, 4)->default(0);
            $table->primary(['product_id','referrer_id']);
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
        Schema::dropIfExists('product_referrer');
    }
}
