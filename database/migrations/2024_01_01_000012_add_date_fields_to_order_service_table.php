<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateFieldsToOrderServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_service', function (Blueprint $table) {
            $table->date('date_from')->nullable()->after('cost');
            $table->date('date_to')->nullable()->after('date_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_service', function (Blueprint $table) {
            $table->dropColumn(['date_from', 'date_to']);
        });
    }
}
