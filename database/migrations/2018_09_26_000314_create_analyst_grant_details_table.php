<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalystGrantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyst_grant_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('analyst_grant_id')->unsigned();
            $table->string('category')->default('');
            $table->string('name')->default('');
            $table->integer('amount');
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
        Schema::dropIfExists('analyst_grant_details');
    }
}
