<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clients, runners and admins all live in the users table (see the note on
 * the class diagram). This adds the role column and the attributes of the
 * Client and Runner subclasses. Runner-only columns stay null for clients
 * and admins, and client-only columns stay null for runners and admins.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // User
            $table->string('role')->default('client')->after('id')->index();
            $table->string('phone')->nullable()->after('email');

            // Client
            $table->string('country')->nullable();
            $table->string('preferred_currency', 3)->nullable();

            // Runner
            $table->string('district')->nullable()->index();
            $table->string('nid_document')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('police_clearance')->nullable();
            $table->string('verification_status')->nullable();
            $table->string('trust_level')->nullable();
            $table->decimal('balance', 12, 2)->default(0);

            // Admin verifies Runner (0..1 admin per runner)
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->string('rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropIndex(['role']);
            $table->dropIndex(['district']);
            $table->dropColumn([
                'role', 'phone', 'country', 'preferred_currency', 'district',
                'nid_document', 'profile_photo', 'police_clearance',
                'verification_status', 'trust_level', 'balance',
                'verified_at', 'rejection_reason',
            ]);
        });
    }
};
