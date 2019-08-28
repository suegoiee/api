<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndDateToCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course', function (Blueprint $table) {
            $table->datetime('end_date')->nullable();
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->datetime('end_date')->nullable();
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
            $table->dropColumn('end_date');
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
    }
}
