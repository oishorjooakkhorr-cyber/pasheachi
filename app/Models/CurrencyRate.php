<?php

namespace App\Models;

use Database\Factories\CurrencyRateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * How many BDT one unit of a foreign currency is worth, set by the admin
 * (F3). The currency code (e.g. "USD") is the primary key, so a rate is
 * found with CurrencyRate::find('USD').
 */
#[Table(key: 'code', keyType: 'string', incrementing: false)]
#[Fillable(['code', 'rate_to_bdt'])]
class CurrencyRate extends Model
{
    /** @use HasFactory<CurrencyRateFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rate_to_bdt' => 'decimal:4',
        ];
    }
}
