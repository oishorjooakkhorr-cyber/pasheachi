<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Exchange rates the admin enters (F3). A standalone lookup table: one row
 * per currency code, e.g. USD => 122.50 means 1 USD = 122.50 BDT.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->string('code', 3)->primary();
            $table->decimal('rate_to_bdt', 12, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
