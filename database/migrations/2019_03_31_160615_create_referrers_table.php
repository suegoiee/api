<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferrersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no');
            $table->string('code');
            $table->string('name')->nullable()->default('');
            $table->string('email');
            $table->double('divided', 4, 4)->default(0);
            $table->string('bank_code')->nullable()->default('');
            $table->string('bank_branch')->nullable()->default('');
            $table->string('bank_name')->nullable()->default('');
            $table->string('bank_account')->nullable()->default('');
            $table->index('code');
            $table->index('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
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
        Schema::dropIfExists('referrers');
    }
}
