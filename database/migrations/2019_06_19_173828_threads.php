<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Threads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id')
                    ->unsigned();
            $table->integer('author_id')
                    ->unsigned()
                    ->index();
            $table->string('subject', 191);
            $table->text('body');
            $table->string('slug', 191)
                    ->unique()
                    ->index();
            $table->integer('solution_reply_id')
                    ->unsigned();
            $table->timestamps();
            $table->foreign('author_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
            $table->foreign('solution_reply_id')
                    ->references('id')->on('replies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
