<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndustriesToCompanyInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_info', function (Blueprint $table) {
            $table->renameColumn('stock_industries', 'industries');
        });
        Schema::table('company_info', function (Blueprint $table) {
           $table->string('stock_industries')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_info', function (Blueprint $table) {
            $table->dropColumn('stock_industries');
        });  
        Schema::table('company_info', function (Blueprint $table) {
            
            $table->renameColumn('industries','stock_industries');
        });
    }
}
