<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteModelAndChangeApiToModelProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('model');
        });
	Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('api','model');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
		$table->renameColumn('model','api');
        });
	Schema::table('products', function (Blueprint $table) {
        
            $table->string('model');
        });
    }
}
