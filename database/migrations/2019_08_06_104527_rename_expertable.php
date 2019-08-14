<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameExpertable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('teachables');
        Schema::create('expertables', function (Blueprint $table) {
            $table->string('expertable_type',191);
            $table->integer('expert_id')->unsigned();
            $table->integer('expertable_id')->unsigned();
            $table->timestamps();
            $table->primary(['expert_id', 'expertable_id', 'expertable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expertables');
        Schema::create('teachables', function (Blueprint $table) {
            $table->string('teachable_type',191);
            $table->integer('expert_id')->unsigned();
            $table->integer('teachable_id')->unsigned();
            $table->timestamps();
            $table->primary(['expert_id', 'teachable_id', 'teachable_type']);
        });
    }
}
