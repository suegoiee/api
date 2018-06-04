<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCodeAndNameToStockCodeAndStockNameFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
		  $table->renameColumn('code','stock_name');
		  $table->renameColumn('name','stock_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
		  $table->renameColumn('stock_code','code');
		  $table->renameColumn('stock_name','name');
        });
    }
}
