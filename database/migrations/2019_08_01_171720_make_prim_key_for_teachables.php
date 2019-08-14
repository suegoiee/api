<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePrimKeyForTeachables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachable', function (Blueprint $table) {
            $table->primary(['expert_id', 'course_id', 'teachable_type']);
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
            $table->dropPrimary(['expert_id', 'course_id', 'teachable_type']);
        });
    }
}
