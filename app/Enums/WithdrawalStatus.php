<?php

namespace App\Enums;

/**
 * State of a runner's bKash payout request (F15).
 */
enum WithdrawalStatus: string
{
    case Requested = 'requested';
    case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Requested => 'Requested',
            self::Paid => 'Paid',
        };
    }
}
