<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySocailitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('socialites');
        Schema::create('socialites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('social_id');
            $table->string('email');
            $table->string('name');
            $table->text('access_token')->nullable();
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
            Schema::dropIfExists('socialites');
         Schema::create('socialites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('type');
            $table->string('social_id');
            $table->string('email');
            $table->string('password');
            $table->string('name');
            $table->string('link');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('locale');
            $table->integer('timezone');
            $table->integer('verified');
            $table->timestamps();
        });
    }
}
