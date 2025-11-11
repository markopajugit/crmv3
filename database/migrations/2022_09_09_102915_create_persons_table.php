<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_zip')->nullable();
            $table->string('address_dropdown')->nullable();
            $table->text('id_code')->nullable();
            $table->text('date_of_birth')->nullable();
            $table->string('iban')->nullable();
            $table->text('country')->nullable();
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
}
