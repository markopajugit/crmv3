<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_person', function (Blueprint $table) {
            $table->id();
            $table->text('related_company')->nullable();
            $table->bigInteger('person_id')->unsigned()->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->text('relation');
            $table->text('selected_email');
            $table->timestamps();

            $table->foreign('person_id')
                ->references('id')
                ->on('persons');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_person');
    }
}
