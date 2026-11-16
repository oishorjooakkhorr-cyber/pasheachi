<?php

namespace App\Models;

use Database\Factories\ProofFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A photo or receipt a runner uploads for an errand (F13).
 */
#[Fillable(['type', 'file_path', 'note', 'uploaded_at'])]
class Proof extends Model
{
    /** @use HasFactory<ProofFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
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
