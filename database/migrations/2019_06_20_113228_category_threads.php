<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoryThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_threads', function (Blueprint $table) {
            $table->increments('id')
                    ->unsigned();
            $table->integer('category_thread_id')
                    ->index();
            $table->integer('tag_id')
                    ->unsigned()
                    ->index();
            $table->string('category_thread_type', 191);
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
        Schema::dropIfExists('category_threads');
    }
}
