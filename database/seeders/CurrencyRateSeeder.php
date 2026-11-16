<?php

namespace Database\Seeders;

use App\Models\CurrencyRate;
use Illuminate\Database\Seeder;

/**
 * Sample exchange rates (BDT per one unit) for the currencies probashis use
 * most. These are placeholders for development; the admin enters the real
 * rates in the app (F3).
 */
class CurrencyRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = [
            'USD' => 122.00,
            'SAR' => 32.50,
            'AED' => 33.20,
            'QAR' => 33.50,
            'KWD' => 397.00,
            'GBP' => 155.00,
            'EUR' => 132.00,
            'MYR' => 27.50,
        ];

        foreach ($rates as $code => $rate) {
            CurrencyRate::updateOrCreate(['code' => $code], ['rate_to_bdt' => $rate]);
        }
    }
}
