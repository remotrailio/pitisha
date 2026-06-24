<?php

namespace App\Enums;

enum ReferralStatus: string
{
    case PENDING   = 'pending';
    case QUALIFIED = 'qualified';
    case CANCELLED = 'cancelled';
}
