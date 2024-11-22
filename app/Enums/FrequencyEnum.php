<?php

namespace App\Enums;

enum FrequencyEnum: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
