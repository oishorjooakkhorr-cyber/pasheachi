<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The central table: one row per errand a client posts (F2).
 *
 * total_cost and is_high_value are derived attributes on the class diagram
 * (/totalCost, /isHighValue), so they are calculated in the model rather
 * than stored here.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('errands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('family_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('errand_category_id')->constrained();
            $table->foreignId('runner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('recurring_errand_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description');
            $table->dateTime('scheduled_at');
            $table->string('status')->default('posted')->index();
            $table->boolean('is_urgent')->default(false);
            $table->decimal('service_fee', 10, 2);
            $table->decimal('item_cost', 10, 2)->default(0);
            $table->decimal('priority_fee', 10, 2)->default(0);
            $table->string('otp_code')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errands');
    }
};
