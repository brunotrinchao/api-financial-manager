<?php

namespace App\Http\Controllers;

use App\Enums\FlagsCreditcardsEnum;
use App\Enums\FrequencyEnum;
use App\Enums\MethodTypeEnum;
use App\Enums\SourceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Resources\AccountResource;
use App\Http\Resources\BankResource;
use App\Http\Resources\CreditCardResource;
use App\Http\Resources\IssuerResource;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Category;
use App\Models\CreditCard;
use App\Models\Issuer;

class SettingsController
{
    public function index()
    {
        $return['frequency'] = FrequencyEnum::getValues();
        $return['method'] = MethodTypeEnum::getValues();
        $return['source']['account'] = AccountResource::collection(Account::orderBy('name', 'ASC')->get());
        $return['source']['creditCard'] = CreditCardResource::collection(CreditCard::orderBy('name', 'ASC')->get());
        $return['status'] = TransactionStatusEnum::getValues();
        $return['type'] = TransactionTypeEnum::getValues();
        $return['issuer'] = IssuerResource::collection(Issuer::get());
        $return['banks'] = BankResource::collection(Bank::orderBy('name', 'ASC')->get());
        return response()->json($return);
    }
}
