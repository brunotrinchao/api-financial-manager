<?php

namespace Database\Seeders;

use App\Enums\FrequencyEnum;
use App\Enums\MethodTypeEnum;
use App\Enums\SourceTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::create([
            'user_id' => 1,
            'category_id' => 1,
            'type' => TransactionTypeEnum::INCOME->value,
            'method' => MethodTypeEnum::PIX->value,
            'amount' => 100.00,
            'description' => 'Grocery shopping',
            'transaction_date' => now()->subDays(5),
            'source_type' => SourceTypeEnum::ACCOUNT->value,
            'source_id' => 1,
            'frequency' => FrequencyEnum::MONTHLY->value, // Frequência da recorrência
            'interval' => 1,          // Intervalo da recorrência
        ]);

        Transaction::create([
            'user_id' => 2,
            'category_id' => 2,
            'type' => TransactionTypeEnum::EXPENSE->value,
            'method' => MethodTypeEnum::CREDIT_CARD->value,
            'amount' => 50.00,
            'description' => 'Bus fare',
            'transaction_date' => now()->subDays(3),
            'source_type' => SourceTypeEnum::CREDIT_CARD->value,
            'source_id' => 2,
            'frequency' => FrequencyEnum::WEEKLY->value,
            'interval' => 1,
        ]);
    }
}
