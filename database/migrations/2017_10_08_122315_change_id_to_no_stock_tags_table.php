<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdToNoStockTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_tags', function (Blueprint $table) {
            $table->renameColumn('stock_id', 'stock_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_tags', function (Blueprint $table) {
            $table->renameColumn('stock_no', 'stock_id');
        });
    }
}
