<?php

namespace App\Enums;

/**
 * A runner's trust level (F18). Only Gold runners can accept high-value errands.
 */
enum TrustLevel: string
{
    case Bronze = 'bronze';
    case Silver = 'silver';
    case Gold = 'gold';

    public function label(): string
    {
        return match ($this) {
            self::Bronze => 'Bronze',
            self::Silver => 'Silver',
            self::Gold => 'Gold',
        };
    }
}
