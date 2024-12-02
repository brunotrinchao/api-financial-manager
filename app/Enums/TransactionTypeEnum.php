<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';
    case TRASNFER = 'transfer';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
