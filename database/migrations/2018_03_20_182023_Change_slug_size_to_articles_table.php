<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSlugSizeToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `articles` ROW_FORMAT=DYNAMIC');
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug', 1024)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug')->change();
        });
        DB::statement('ALTER TABLE `articles` ROW_FORMAT=COMPACT');
    }
}
