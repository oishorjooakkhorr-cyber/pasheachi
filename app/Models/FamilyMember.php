<?php

namespace App\Models;

use Database\Factories\FamilyMemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A parent or relative a client cares for (F1). Has no account of their own.
 */
#[Fillable(['name', 'relation', 'phone', 'address', 'district', 'care_notes'])]
class FamilyMember extends Model
{
    /** @use HasFactory<FamilyMemberFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Errands done for this family member; the care history (F6).
     *
     * @return HasMany<Errand, $this>
     */
    public function errands(): HasMany
    {
        return $this->hasMany(Errand::class);
    }

    /**
     * @return HasMany<RecurringErrand, $this>
     */
    public function recurringErrands(): HasMany
    {
        return $this->hasMany(RecurringErrand::class);
    }
}
