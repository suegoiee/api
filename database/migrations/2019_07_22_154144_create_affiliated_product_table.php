<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliatedProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliated_product', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('affiliated_product_id')->unsigned();
            $table->integer('sort')->default(0);
            $table->timestamps();
            
            $table->primary(['product_id', 'affiliated_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliated_product');
    }
}
