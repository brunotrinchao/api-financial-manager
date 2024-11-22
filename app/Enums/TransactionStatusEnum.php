<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case PENDING = 'pending';
    case SCHEDULED = 'scheduled';
    case PAID = 'paid';
    case CANCELED = 'canceled';
    case OVERDUE = 'overdue';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
