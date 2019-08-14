<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeExpertCourseToTeachable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('expert_course', 'teachable');
        Schema::table('teachable', function (Blueprint $table) {
            $table->string('teachable_type',191);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('teachable', 'expert_course');
        Schema::table('expert_course', function (Blueprint $table) {
            $table->dropColumn('teachable_type');
        });
    }
}
