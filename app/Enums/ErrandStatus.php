<?php

namespace App\Enums;

/**
 * The steps an errand moves through, in order (F5).
 */
enum ErrandStatus: string
{
    case Posted = 'posted';
    case Accepted = 'accepted';
    case OnTheWay = 'on_the_way';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Posted => 'Posted',
            self::Accepted => 'Accepted',
            self::OnTheWay => 'On the Way',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
        };
    }
}
