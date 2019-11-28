<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_functions', function (Blueprint $table) {
            $table->integer('function_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->timestamps();
            $table->primary(['function_id', 'role_id'], 'role_functions_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_functions');
    }
}
