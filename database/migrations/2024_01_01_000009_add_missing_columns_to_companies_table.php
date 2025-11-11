<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('number')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->text('address_note')->nullable()->after('address_dropdown');
            $table->text('email_note')->nullable()->after('email');
            $table->text('phone_note')->nullable()->after('phone');
            $table->boolean('deleted')->default(false)->after('phone_note');
            $table->date('kyc_start')->nullable()->after('deleted');
            $table->date('kyc_end')->nullable()->after('kyc_start');
            $table->text('kyc_reason')->nullable()->after('kyc_end');
            $table->text('tax_residency')->nullable()->after('kyc_reason');
            $table->string('activity_code')->nullable()->after('tax_residency');
            $table->text('activity_code_description')->nullable()->after('activity_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'number',
                'phone',
                'address_note',
                'email_note',
                'phone_note',
                'deleted',
                'kyc_start',
                'kyc_end',
                'kyc_reason',
                'tax_residency',
                'activity_code',
                'activity_code_description'
            ]);
        });
    }
}
