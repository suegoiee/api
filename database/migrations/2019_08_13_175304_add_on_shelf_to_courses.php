<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnShelfToCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course', function (Blueprint $table) {
            $table->integer('on_shelf')->unsigned()->default(0);
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->integer('on_shelf')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_course', function (Blueprint $table) {
            $table->dropColumn('on_shelf');
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->dropColumn('on_shelf');
        });
    }
}
