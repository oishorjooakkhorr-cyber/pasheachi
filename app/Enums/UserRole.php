<?php

namespace App\Enums;

/**
 * The three kinds of account. The class diagram draws Client, Runner and
 * Admin as subclasses of User; in the database they share the users table
 * and this value goes in its role column.
 */
enum UserRole: string
{
    case Client = 'client';
    case Runner = 'runner';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Client => 'Client',
            self::Runner => 'Runner',
            self::Admin => 'Admin',
        };
    }
}
