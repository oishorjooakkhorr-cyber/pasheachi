<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Errand types such as "Doctor visit escort", with the base fee the admin
 * sets (F19). Inactive categories are hidden from the post-errand form.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('errand_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('base_service_fee', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errand_categories');
    }
};
