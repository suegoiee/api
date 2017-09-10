<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['stock_id']);
            $table->dropPrimary(['stock_id', 'user_id']);
            $table->dropColumn('stock_id');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->increments('id')->first();
            $table->string('code',6)->unique()->after('id');
            $table->string('name')->after('code');
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
        Schema::table('favorites', function (Blueprint $table) {
            $table->integer('id')->change();
            $table->dropPrimary('id');
            $table->dropColumn('id');
            $table->dropColumn(['code','name']);
            $table->dropColumn('delete_at');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->integer('stock_id')->unsigned()->first();
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
            $table->primary(['stock_id', 'user_id']);
        });
    }
}
