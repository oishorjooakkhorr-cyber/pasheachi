<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A client's complaint about an errand, resolved by an admin (F10). An
 * errand has 0..1 dispute, so errand_id is unique.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('errand_id')->unique()->constrained();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason');
            $table->text('description');
            $table->string('status')->default('open')->index();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->text('resolution_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
