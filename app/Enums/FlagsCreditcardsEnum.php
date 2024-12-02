<?php

namespace App\Enums;

enum FlagsCreditcardsEnum: string
{
    case VISA = 'Visa';
    case MARSTER_CARD = 'MasterCard';
    case AMERICAN_EXPRESS = 'American Express';
    case ELO = 'Elo';
    case HIPER_CARD = 'Hipercard';
    case DINERS_CLUB = 'Diners Club';
    case DISCOVER = 'Discover';
    case JBC = 'JCB';
    case AURA = 'Aura';
    case UNION_PAY = 'UnionPay';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
