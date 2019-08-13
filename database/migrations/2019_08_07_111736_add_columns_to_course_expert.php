<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCourseExpert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course', function (Blueprint $table) {
            $table->text('host');
            $table->integer('interested');
            $table->text('Suitable');
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->text('host');
            $table->integer('interested');
            $table->text('Suitable');
        });
        Schema::table('experts', function (Blueprint $table) {
            $table->text('book');
            $table->text('interview');
            $table->integer('investment_style')->change();
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
            $table->dropColumn('host');
            $table->dropColumn('interested');
            $table->dropColumn('Suitable');
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->dropColumn('host');
            $table->dropColumn('interested');
            $table->dropColumn('Suitable');
        });
        Schema::table('experts', function (Blueprint $table) {
            $table->dropColumn('book');
            $table->dropColumn('interview');
            $table->string('investment_style',191)->change();
        });
    }
}
