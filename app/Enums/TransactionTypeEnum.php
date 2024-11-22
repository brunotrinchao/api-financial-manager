<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
