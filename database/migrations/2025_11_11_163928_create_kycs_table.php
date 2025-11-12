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
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->string('kycable_type');
            $table->unsignedBigInteger('kycable_id');
            $table->text('comments')->nullable();
            $table->string('risk')->nullable();
            $table->text('documents')->nullable();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->index(['kycable_type', 'kycable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
