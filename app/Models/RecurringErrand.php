<?php

namespace App\Models;

use Database\Factories\RecurringErrandFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A monthly rule that creates an errand automatically (F7).
 */
#[Fillable([
    'family_member_id', 'errand_category_id', 'day_of_month',
    'estimated_item_cost', 'next_run_date', 'is_active',
])]
class RecurringErrand extends Model
{
    /** @use HasFactory<RecurringErrandFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'day_of_month' => 'integer',
            'estimated_item_cost' => 'decimal:2',
            'next_run_date' => 'date',
            'is_active' => 'boolean',
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
     * Errands this rule has generated.
     *
     * @return HasMany<Errand, $this>
     */
    public function errands(): HasMany
    {
        return $this->hasMany(Errand::class);
    }
}
