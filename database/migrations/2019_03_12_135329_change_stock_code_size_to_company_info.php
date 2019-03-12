<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStockCodeSizeToCompanyInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_info', function (Blueprint $table) {
            $table->string('stock_code',20)->change();
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
            $table->string('stock_code',4)->change();
        });
    }
}
