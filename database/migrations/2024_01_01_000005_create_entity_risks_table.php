<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntityRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_risks', function (Blueprint $table) {
            $table->id();
            $table->morphs('riskable');
            $table->string('risk_level'); // low, medium, high
            $table->text('assessment')->nullable();
            $table->text('mitigation')->nullable();
            $table->bigInteger('assessed_by')->unsigned()->nullable();
            $table->date('assessment_date')->nullable();
            $table->date('review_date')->nullable();
            $table->timestamps();

            $table->foreign('assessed_by')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('entity_risks');
    }
}
