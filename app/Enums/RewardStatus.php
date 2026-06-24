<?php

namespace App\Enums;

enum RewardStatus: string
{
    case PENDING = 'pending';
    case EARNED  = 'earned';
    case CLAIMED = 'claimed';
    case REVOKED = 'revoked';
}
