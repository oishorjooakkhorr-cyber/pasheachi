<?php

namespace App\Models;

use App\Enums\ErrandStatus;
use Database\Factories\ErrandFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * A task a client posts for a family member (F2). The business methods from
 * the class diagram (calculateCost, updateStatus, verifyOtp, ...) are added
 * by the features that need them.
 */
#[Fillable([
    'family_member_id', 'errand_category_id', 'runner_id', 'recurring_errand_id',
    'description', 'scheduled_at', 'status', 'is_urgent',
    'service_fee', 'item_cost', 'priority_fee', 'otp_code', 'completed_at',
])]
#[Hidden(['otp_code'])]
class Errand extends Model
{
    /** @use HasFactory<ErrandFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'status' => ErrandStatus::class,
            'is_urgent' => 'boolean',
            'service_fee' => 'decimal:2',
            'item_cost' => 'decimal:2',
            'priority_fee' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * @return BelongsTo<FamilyMember, $this>
     */
    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class);
    }

    /**
     * @return BelongsTo<ErrandCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ErrandCategory::class, 'errand_category_id');
    }

    /**
     * The runner doing this errand; null until one accepts it.
     *
     * @return BelongsTo<User, $this>
     */
    public function runner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'runner_id');
    }

    /**
     * The monthly rule that created this errand, if any (F7).
     *
     * @return BelongsTo<RecurringErrand, $this>
     */
    public function recurringErrand(): BelongsTo
    {
        return $this->belongsTo(RecurringErrand::class);
    }

    /**
     * @return HasMany<Proof, $this>
     */
    public function proofs(): HasMany
    {
        return $this->hasMany(Proof::class);
    }

    /**
     * @return HasOne<Payment, $this>
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * @return HasOne<Review, $this>
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * @return HasOne<Dispute, $this>
     */
    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class);
    }
}
