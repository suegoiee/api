<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseGroupMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_group_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leader_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->string('course_type');
            $table->string('email');
            $table->string('phone');
            $table->string('name');
            $table->string('comments');
            $table->string('address');
            $table->integer('sex')->unsigned();
            $table->integer('order_id')->unsigned();
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
        Schema::dropIfExists('course_group_members');
    }
}
