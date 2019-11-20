<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAliasNameToProductsAndLab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('alias')->nullable()->default('');
        });
        Schema::table('laboratories', function (Blueprint $table) {
            $table->string('alias')->nullable()->default('');
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
            $table->dropColumn('alias');
        });
        Schema::table('laboratories', function (Blueprint $table) {
            $table->dropColumn('alias');
        });
    }
}
