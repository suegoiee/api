<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTitleAndAddInstalledToProductUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_user', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dateTime('deadline')->change();
            $table->integer('installed')->after('deadline');
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
        Schema::table('product_user', function (Blueprint $table) {
            $table->date('deadline')->change();
            $table->dropColumn('delete_at');
            $table->dropColumn('installed');
            $table->string('title')->nullable();
        });
    }
}
