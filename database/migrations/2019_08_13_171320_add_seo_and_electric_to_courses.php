<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoAndElectricToCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course', function (Blueprint $table) {
            $table->string('seo', 250)->nullable();
            $table->text('electric_ticket')->nullable();
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->string('seo', 250)->nullable();
            $table->text('electric_ticket')->nullable();
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
            $table->dropColumn('seo');
            $table->dropColumn('electric_ticket');
        });
        Schema::table('physical_course', function (Blueprint $table) {
            $table->dropColumn('seo');
            $table->dropColumn('electric_ticket');
        });
    }
}
