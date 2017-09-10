<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusExpirationAndSoftdeleteToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('model')->nullable()->change();
            $table->text('info_more')->nullable()->change();
     		$table->integer('status')->default(0);
    		$table->integer('expiration');
    		$table->softDeletes();
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
            $table->string('model')->change();
            $table->text('info_more')->change();
    		$table->dropColumn('status');
    		$table->dropColumn('expiration');
    		$table->dropColumn('delete_at');
        });
    }
}
