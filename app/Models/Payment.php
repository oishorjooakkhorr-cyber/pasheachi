<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The client's escrow payment for one errand (F8).
 */
#[Fillable([
    'errand_id', 'amount_bdt', 'currency', 'amount_foreign',
    'exchange_rate', 'commission', 'status', 'paid_at',
])]
class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount_bdt' => 'decimal:2',
            'amount_foreign' => 'decimal:2',
            'exchange_rate' => 'decimal:4',
            'commission' => 'decimal:2',
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Errand, $this>
     */
    public function errand(): BelongsTo
    {
        return $this->belongsTo(Errand::class);
    }
}
