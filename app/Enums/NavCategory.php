<?php

namespace App\Enums;

enum NavCategory: string
{
    case Music       = 'Music';
    case Tech        = 'Tech';
    case Sports      = 'Sports';
    case Conferences = 'Conferences';

    public static function names(): array
    {
        return array_column(self::cases(), 'value');
    }
}
