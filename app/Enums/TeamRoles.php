<?php

namespace App\Enums;

enum TeamRoles: string
{
    // case Leader = 'Leader';
    case Admin = 'Admin';
    case Member = 'Member';

    public static function admin(): array
    {
        return [self::Admin->name => self::Admin->value];
    }
}
