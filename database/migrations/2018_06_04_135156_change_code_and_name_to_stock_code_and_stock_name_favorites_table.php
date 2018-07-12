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
		      $table->renameColumn('code','stock_code');
		      $table->renameColumn('name','stock_name');
              $table->dropUnique('favorites_code_unique');
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
          //$table->string('code',6)->unique()->change();
        });
    }
}
