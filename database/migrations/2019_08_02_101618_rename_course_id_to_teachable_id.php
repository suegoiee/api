<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCourseIdToTeachableId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachable', function (Blueprint $table) {
            $table->renameColumn('course_id', 'teachable_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachable', function (Blueprint $table) {
            $table->renameColumn('teachable_id', 'course_id');
        });
    }
}
