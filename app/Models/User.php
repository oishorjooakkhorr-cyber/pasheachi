<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\TrustLevel;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Every account: clients, runners and admins.
 *
 * The class diagram shows Client, Runner and Admin as subclasses of an
 * abstract User. In Laravel they share this one model and the users table;
 * the role column says which kind a user is. Relationships that only make
 * sense for one role are grouped below under that role's heading.
 */
#[Fillable([
    'name', 'email', 'password', 'role', 'phone',
    'country', 'preferred_currency',
    'district', 'nid_document', 'profile_photo', 'police_clearance',
    'verification_status', 'trust_level', 'balance',
    'verified_by', 'verified_at', 'rejection_reason',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'verification_status' => VerificationStatus::class,
            'trust_level' => TrustLevel::class,
            'balance' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Whether the user has one of the given roles.
     */
    public function hasRole(UserRole ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isClient(): bool
    {
        return $this->role === UserRole::Client;
    }

    public function isRunner(): bool
    {
        return $this->role === UserRole::Runner;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Only clients, e.g. User::clients()->get().
     *
     * @param  Builder<User>  $query
     */
    #[Scope]
    protected function clients(Builder $query): void
    {
        $query->where('role', UserRole::Client);
    }

    /**
     * Only runners, e.g. User::runners()->where('district', 'Sylhet')->get().
     *
     * @param  Builder<User>  $query
     */
    #[Scope]
    protected function runners(Builder $query): void
    {
        $query->where('role', UserRole::Runner);
    }

    /**
     * Only admins.
     *
     * @param  Builder<User>  $query
     */
    #[Scope]
    protected function admins(Builder $query): void
    {
        $query->where('role', UserRole::Admin);
    }

    /**
     * In-app notifications (F16), stored in the notifications table from the
     * class diagram. This replaces the notifications() method that Laravel's
     * Notifiable trait provides; mail notifications such as password reset
     * still work because they do not use this table.
     *
     * @return HasMany<Notification, $this>
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ---- Client ------------------------------------------------------

    /**
     * Family members this client cares for (F1).
     *
     * @return HasMany<FamilyMember, $this>
     */
    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class, 'client_id');
    }

    /**
     * Errands this client has posted (F2).
     *
     * @return HasMany<Errand, $this>
     */
    public function postedErrands(): HasMany
    {
        return $this->hasMany(Errand::class, 'client_id');
    }

    /**
     * Monthly errand rules this client has set up (F7).
     *
     * @return HasMany<RecurringErrand, $this>
     */
    public function recurringErrands(): HasMany
    {
        return $this->hasMany(RecurringErrand::class, 'client_id');
    }

    // ---- Runner ------------------------------------------------------

    /**
     * Errands assigned to this runner (F4, F12).
     *
     * @return HasMany<Errand, $this>
     */
    public function assignedErrands(): HasMany
    {
        return $this->hasMany(Errand::class, 'runner_id');
    }

    /**
     * Payout requests this runner has made (F15).
     *
     * @return HasMany<Withdrawal, $this>
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'runner_id');
    }

    /**
     * The admin who approved or rejected this runner (F19).
     *
     * @return BelongsTo<User, $this>
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ---- Admin -------------------------------------------------------

    /**
     * Runners this admin has approved or rejected (F19).
     *
     * @return HasMany<User, $this>
     */
    public function verifiedRunners(): HasMany
    {
        return $this->hasMany(User::class, 'verified_by');
    }

    /**
     * Disputes this admin has resolved (F10).
     *
     * @return HasMany<Dispute, $this>
     */
    public function resolvedDisputes(): HasMany
    {
        return $this->hasMany(Dispute::class, 'resolved_by');
    }
}
