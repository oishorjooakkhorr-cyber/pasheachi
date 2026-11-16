<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A runner's request to be paid out to a bKash number (F15, simulated).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('runner_id')->constrained('users');
            $table->decimal('amount', 12, 2);
            $table->string('bkash_number');
            $table->string('status')->default('requested');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
