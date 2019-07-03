<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->increments('id')
                    ->unsigned();
            $table->integer('author_id')
                    ->unsigned()
                    ->index();
            $table->string('subject',191);
            $table->text('body');
            $table->string('slug',191)
                    ->unique()
                    ->index();
            $table->integer('solution_reply_id')
                    ->unsigned()
                    ->index();
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
        Schema::dropIfExists('archives');
    }
}
