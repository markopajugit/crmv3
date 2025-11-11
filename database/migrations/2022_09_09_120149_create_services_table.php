<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('cost');
            $table->bigInteger('service_category_id')->unsigned()->nullable();
            $table->text('type')->nullable();
            $table->text('reaccuring_frequency')->nullable();
            $table->timestamps();

            $table->foreign('service_category_id')
                ->references('id')
                ->on('service_category')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
