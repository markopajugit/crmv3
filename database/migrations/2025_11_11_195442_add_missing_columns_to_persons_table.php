<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->text('birthplace_country')->nullable()->after('date_of_birth');
            $table->text('birthplace_city')->nullable()->after('birthplace_country');
            $table->text('tax_residency')->nullable()->after('country');
            $table->text('citizenship')->nullable()->after('tax_residency');
            $table->tinyInteger('pep')->nullable()->after('citizenship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn(['citizenship', 'birthplace_country', 'birthplace_city', 'pep', 'tax_residency']);
        });
    }
};
