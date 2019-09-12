<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelatedProductsFieldsToNotificationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_messages', function (Blueprint $table) {
            $table->text('product_ids')->nullable();
            $table->integer('expired_user')->default(0);
            $table->integer('non_expired_user')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_messages', function (Blueprint $table) {
            $table->dropColumn('product_ids');
            $table->dropColumn('expired_user');
            $table->dropColumn('non_expired_user');
        });
    }
}
