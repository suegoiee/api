<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condition_product', function (Blueprint $table) {
            $table->integer('event_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->double('discount',8,4)->default(0);
            $table->integer('quantity')->default(0);
            $table->primary(['product_id','event_id']);
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
        Schema::dropIfExists('condition_product');
    }
}
