<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBanToThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->integer('ban')
                    ->unsigned()
                    ->nullable();
        });
        Schema::table('replies', function (Blueprint $table) {
            $table->integer('ban')
                    ->unsigned()
                    ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->dropColumn('ban');
        });
        Schema::table('replies', function (Blueprint $table) {
            $table->dropColumn('ban');
        });
    }
}
