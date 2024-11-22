<?php

namespace App\Enums;

enum MethodTypeEnum: string
{
    case ACCOUNT = 'account';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case PIX = 'pix';
    case CASH = 'cash';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
