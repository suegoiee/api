<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->increments('id')
                    ->unsigned();
            $table->text('body');
            $table->integer('author_id')
                    ->unsigned()
                    ->index();
            $table->integer('replyable_id')
                    ->unsigned()
                    ->index();
            $table->string('replyable_type',191);
            $table->timestamps();
            $table->foreign('author_id')
                    ->references('id')->on('users')
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
        Schema::dropIfExists('replies');
    }
}
