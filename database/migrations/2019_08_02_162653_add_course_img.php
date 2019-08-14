<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseImg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course', function (Blueprint $table) {
            $table->string('image',191);
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->string('image',191);
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
            $table->dropColumn('image');
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
