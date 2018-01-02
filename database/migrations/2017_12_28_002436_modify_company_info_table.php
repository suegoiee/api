<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCompanyInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_info', function (Blueprint $table) {
            $table->renameColumn('Stock_ID', 'stock_code');
            $table->renameColumn('name', 'stock_name');
            $table->renameColumn('industry', 'stock_industries');
            $table->text('supplier')->nullable();
            $table->text('info')->nullable()->change();
            $table->text('product')->nullable()->change();
            $table->text('area')->nullable()->change();
            $table->text('product')->nullable()->change();
            $table->text('customer')->nullable()->change();
            $table->text('link')->nullable()->change();
            $table->text('local_related_1')->nullable();
            $table->text('local_related_2')->nullable();
            $table->text('local_related_3')->nullable();
            $table->text('local_related_4')->nullable();
            $table->text('local_related_5')->nullable();
            $table->text('foreign_related')->nullable();
            $table->timestamp('created_at')->nullable();
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
            $table->renameColumn('stock_code', 'Stock_ID');
            $table->renameColumn('stock_name', 'name');
            $table->renameColumn('stock_industries', 'industry');
            $table->dropColumn('supplier');
            $table->text('info')->nullable(false)->change();
            $table->text('product')->nullable(false)->change();
            $table->text('area')->nullable(false)->change();
            $table->text('product')->nullable(false)->change();
            $table->text('customer')->nullable(false)->change();
            $table->text('link')->nullable(false)->change();
            $table->dropColumn('local_related_1');
            $table->dropColumn('local_related_2');
            $table->dropColumn('local_related_3');
            $table->dropColumn('local_related_4');
            $table->dropColumn('local_related_5');
            $table->dropColumn('foreign_related');
            $table->dropColumn('created_at');
        });
    }
}
