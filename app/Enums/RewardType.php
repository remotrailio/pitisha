<?php

namespace App\Enums;

enum RewardType: string
{
    case FREE_TICKET  = 'free_ticket';
    case DISCOUNT     = 'discount';
    case UPGRADE      = 'upgrade';
    case MERCHANDISE  = 'merchandise';

    public function label(): string
    {
        return match ($this) {
            self::FREE_TICKET => 'Free Ticket',
            self::DISCOUNT    => 'Discount',
            self::UPGRADE     => 'Upgrade',
            self::MERCHANDISE => 'Merchandise',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn ($case) => ['value' => $case->value, 'label' => $case->label()], self::cases()),
            'label',
            'value'
        );
    }
}
