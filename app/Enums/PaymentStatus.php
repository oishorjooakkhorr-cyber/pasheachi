<?php

namespace App\Enums;

/**
 * Escrow states of a payment (F8, F10): held when the client pays, released
 * to the runner after the family OTP, refunded if the admin upholds a dispute.
 */
enum PaymentStatus: string
{
    case Held = 'held';
    case Released = 'released';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Held => 'Held',
            self::Released => 'Released',
            self::Refunded => 'Refunded',
        };
    }
}
