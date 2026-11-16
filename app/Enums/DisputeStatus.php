<?php

namespace App\Enums;

/**
 * State of a client's dispute (F10).
 */
enum DisputeStatus: string
{
    case Open = 'open';
    case Resolved = 'resolved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Resolved => 'Resolved',
            self::Rejected => 'Rejected',
        };
    }
}
