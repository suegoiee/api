<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysicalCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_course', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',191);
            $table->datetime('date')->nullable();
            $table->string('location',191);
            $table->integer('quota')->unsigned();
            $table->text('introduction')->nullable();
            $table->integer('status')->unsigned();
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
        Schema::dropIfExists('physical_course');
    }
}
