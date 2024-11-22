<?php

namespace App\Enums;

enum SourceTypeEnum: string
{
    case ACCOUNT = 'account';
    case CREDIT_CARD = 'credit_card';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
