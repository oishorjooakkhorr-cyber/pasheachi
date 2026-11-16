<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * In-app notifications (F16). Composition with User: deleting a user
 * deletes their notifications.
 *
 * This follows the class diagram rather than Laravel's built-in database
 * notifications table, so App\Models\User overrides notifications().
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
