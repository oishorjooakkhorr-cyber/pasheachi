<?php

namespace App\Models;

use App\Enums\DisputeStatus;
use Database\Factories\DisputeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A client's complaint about an errand, resolved by an admin (F10).
 */
#[Fillable([
    'errand_id', 'reason', 'description', 'status',
    'refund_amount', 'resolution_note', 'resolved_by',
])]
class Dispute extends Model
{
    /** @use HasFactory<DisputeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => DisputeStatus::class,
            'refund_amount' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<Errand, $this>
     */
    public function errand(): BelongsTo
    {
        return $this->belongsTo(Errand::class);
    }

    /**
     * The admin who resolved or rejected the dispute.
     *
     * @return BelongsTo<User, $this>
     */
    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
