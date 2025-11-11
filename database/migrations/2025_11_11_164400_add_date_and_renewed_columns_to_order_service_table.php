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
        Schema::table('order_service', function (Blueprint $table) {
            $table->string('date_from')->nullable()->after('cost');
            $table->string('date_to')->nullable()->after('date_from');
            $table->boolean('renewed')->default(false)->after('date_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_service', function (Blueprint $table) {
            $table->dropColumn(['date_from', 'date_to', 'renewed']);
        });
    }
};
