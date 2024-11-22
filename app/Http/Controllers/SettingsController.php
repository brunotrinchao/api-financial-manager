<?php

namespace App\Http\Controllers;

use App\Enums\FrequencyEnum;
use App\Enums\MethodTypeEnum;
use App\Enums\SourceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Category;

class SettingsController
{
    public function index()
    {
        $return['frequency'] = FrequencyEnum::getValues();
        $return['method'] = MethodTypeEnum::getValues();
        $return['source'] = SourceTypeEnum::getValues();
        $return['status'] = TransactionStatusEnum::getValues();
        $return['type'] = TransactionTypeEnum::getValues();
        return response()->json($return);
    }
}
