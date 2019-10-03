<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraInfosToOrderCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_course', function (Blueprint $table) {
            $table->integer('paid')->unsigned();
            $table->string('source');
            $table->string('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_course', function (Blueprint $table) {
            $table->dropColumn('paid');
            $table->dropColumn('source');
            $table->dropColumn('remarks');
        });
    }
}
