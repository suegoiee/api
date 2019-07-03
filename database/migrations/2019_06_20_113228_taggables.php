<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Taggables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->increments('id')
                    ->unsigned();
            $table->integer('taggable_id')
                    ->index();
            $table->integer('tag_id')
                    ->unsigned()
                    ->index();
            $table->string('taggable_type', 191);
            $table->timestamps();
            $table->foreign('tag_id')
                    ->references('id')->on('categories')
                    ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taggables');
    }
}
