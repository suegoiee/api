<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHeadToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('invoice_head', 'invoice_title');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->renameColumn('invoice_head', 'invoice_title');
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
            $table->renameColumn('invoice_title', 'invoice_head');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->renameColumn('invoice_title', 'invoice_head');
        });
    }
}
