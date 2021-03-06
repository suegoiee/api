<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferrerGrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrer_grants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('referrer_id')->unsigned();
            $table->string('statement_no')->nullable();
            $table->string('year_month');
            $table->integer('price')->default(0);
            $table->integer('handle_fee')->default(0);
            $table->integer('platform_fee')->default(0);
            $table->integer('income_tax')->default(0);
            $table->integer('second_generation_nhi')->default(0);
            $table->integer('interbank_remittance_fee')->default(0);
            $table->double('divided', 4, 4)->default(0);
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
        Schema::dropIfExists('referrer_grants');
    }
}
