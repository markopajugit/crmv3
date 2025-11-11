<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->bigInteger('responsible_user_id')->unsigned();
            $table->bigInteger('person_id')->unsigned()->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->text('status')->default('Not Active');
            $table->text('payment_status')->default('Not paid');
            $table->text('awaiting_status')->nullable();
            //$table->bigInteger('order_contact')->unsigned()->nullable();
            $table->boolean('notification_sent')->default(false);
            $table->text('custom_contact_person_data')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_date')->nullable();
            $table->timestamps();

            $table->foreign('responsible_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('person_id')
                ->references('id')
                ->on('persons')
                ->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
