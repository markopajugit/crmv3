<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->text('id_code_est')->nullable()->after('id_code');
            $table->text('tax_residency')->nullable()->after('phone');
            $table->text('address_note')->nullable()->after('address_dropdown');
            $table->text('email_note')->nullable()->after('email');
            $table->text('phone_note')->nullable()->after('phone');
            $table->string('birthplace_country')->nullable()->after('country');
            $table->string('birthplace_city')->nullable()->after('birthplace_country');
            $table->string('citizenship')->nullable()->after('birthplace_city');
            $table->boolean('pep')->default(false)->after('citizenship');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn([
                'id_code_est',
                'tax_residency',
                'address_note',
                'email_note',
                'phone_note',
                'birthplace_country',
                'birthplace_city',
                'citizenship',
                'pep'
            ]);
        });
    }
}
