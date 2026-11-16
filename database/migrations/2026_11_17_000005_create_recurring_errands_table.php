<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Monthly rules a client sets up (F7). The scheduler reads active rules
 * and creates an errand on next_run_date.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recurring_errands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('family_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('errand_category_id')->constrained();
            $table->unsignedTinyInteger('day_of_month');
            $table->decimal('estimated_item_cost', 10, 2)->default(0);
            $table->date('next_run_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'next_run_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_errands');
    }
};
