<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollectionProductIdToProductIdFromLaboratoriesTble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->renameColumn('collection_product_id', 'product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->renameColumn('product_id', 'collection_product_id');
        });
    }
}
