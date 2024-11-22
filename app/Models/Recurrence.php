<?php

namespace App\Models;

use App\Enums\FrequencyEnum;
use Illuminate\Database\Eloquent\Model;

class Recurrence extends Model
{
    protected $fillable = ['frequency', 'interval', 'end_date', 'next_occurrence'];

    protected $casts = [
        'frequency' => FrequencyEnum::class,
    ];
}
