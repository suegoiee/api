<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNumberToCompanyIdInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('invoice_number', 'company_id');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->renameColumn('invoice_number', 'company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('company_id', 'invoice_number');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->renameColumn('company_id', 'invoice_number');
        });
    }
}
