<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The escrow payment for an errand (F8). An errand has 0..1 payment, so
 * errand_id is unique. The exchange rate is copied in at payment time so
 * the receipt stays correct if the admin changes the rate later.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('errand_id')->unique()->constrained();
            $table->decimal('amount_bdt', 12, 2);
            $table->string('currency', 3);
            $table->decimal('amount_foreign', 12, 2);
            $table->decimal('exchange_rate', 12, 4);
            $table->decimal('commission', 12, 2)->default(0);
            $table->string('status')->default('held')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
