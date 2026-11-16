<?php

namespace App\Models;

use Database\Factories\ErrandCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A type of errand with its base fee, managed by the admin (F19).
 */
#[Fillable(['name', 'base_service_fee', 'is_active'])]
class ErrandCategory extends Model
{
    /** @use HasFactory<ErrandCategoryFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'base_service_fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
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
