<?php

namespace App\Http\Requests\Transaction;

use App\Enums\FrequencyEnum;
use App\Enums\MethodTypeEnum;
use App\Enums\SourceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\CreditCard;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|string|in:' . implode(',', TransactionTypeEnum::getValues()),
            'method' => 'required_if:type,expense|string',
            'interval' => 'required_if:type,expense|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'start_date' => 'required_if:interval,>1,type,expense|date',
            'source_type' => [
                'nullable',
                'in:' . implode(',', SourceTypeEnum::getValues()),
                function ($attribute, $value, $fail) {
                    if ($this->input('type') !== TransactionTypeEnum::TRASNFER->value && is_null($value)) {
                        $fail('Fonte é obrigatório.');
                    }
                },
            ],
            'source_id' => [
                function ($attribute, $value, $fail) {
                    if ($this->input('type') !== 'transfer' && is_null($value)) {
                        $fail('Fonte é obrigatório.');
                    }
                },
                'nullable',
                function ($attribute, $value, $fail) {
                    $sourceType = $this->input('source_type'); // Obtém o valor de `source_type`

                    if ($sourceType === 'account') {
                        if (!DB::table('accounts')->where('id', $value)->exists()) {
                            $fail('O source_id não existe na tabela accounts.');
                        }
                    } elseif ($sourceType === 'creditcard') {
                        if (!DB::table('credit_cards')->where('id', $value)->exists()) {
                            $fail('O source_id não existe na tabela credit_cards.');
                        }
                    }
                },
            ],
            'frequency' => [
                'required_if:type,expense|in:' . implode(',', FrequencyEnum::getValues()),
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->interval > 1 && $this->type === 'expense' && is_null($value)) {
                        $fail('O campo "Frequência" é obrigatório para despesas recorrentes.');
                    }
                }
            ],
            'from_account' => [
                'required_if:type,transfer|exists:accounts,id',
            ],
            'to_account' => [
                'required_if:type,transfer|exists:accounts,id',
            ],
        ];
    }

    public function processedTransactions()
    {

        $data = $this->validated();
        $transactions = [];
        $transactionDate = Carbon::parse($data['transaction_date']);
        $frequencyMap = [
            'daily' => 'addDay',
            'weekly' => 'addWeek',
            'monthly' => 'addMonth',
            'yearly' => 'addYear',
        ];

        $data['frequency'] = $data['frequency'] ?? 'monthly';
        $installments_amount = $data['amount'] / $data['interval'];

        for ($i = 0; $i < $data['interval']; $i++) {
            $data['amount'] = $installments_amount;
            $data['user_id'] = $this->user()->id;
            $data['installment'] = $i + 1;

            if ($data['type'] === 'transfer') {
                // Cria transação de saída
                $transactions[] = array_merge($data, [
                    'type' => TransactionTypeEnum::TRASNFER->value,
                    'source_type' => $data['from_account']['type'],
                    'source_id' => $data['from_account']['id'],
                    'method' => MethodTypeEnum::ACCOUNT->value,
                    'status' => TransactionStatusEnum::PAID->value
                ]);

                // Cria transação de entrada
                $transactions[] = array_merge($data, [
                    'type' => TransactionTypeEnum::TRASNFER->value,
                    'source_type' => $data['from_account']['type'],
                    'source_id' => $data['to_account']['id'],
                    'method' => MethodTypeEnum::ACCOUNT->value,
                    'status' => TransactionStatusEnum::PAID->value
                ]);
            } else {
                $transactions[] = array_merge($data, [
                    'type' => $data['type'],
                    'amount' => $installments_amount,
                ]);
            }

            $transactionDate->{$frequencyMap[$data['frequency']]}();
        }
//        $data = $this->validated();
//        $transactions = [];
//        $transactionDate = Carbon::parse($data['transaction_date']);
//        $frequencyMap = [
//            'daily' => 'addDay',
//            'weekly' => 'addWeek',
//            'monthly' => 'addMonth',
//            'yearly' => 'addYear',
//        ];
//
//
//        $data['frequency'] = $data['frequency'] ?? 'monthly';
//        $installments_amount = $data['amount'] / $data['interval'];
//
//        for ($i = 0; $i < $data['interval']; $i++) {
//            $data['amount'] = $installments_amount;
//            $data['user_id'] = $this->user()->id;
//            $data['installment'] = $i + 1;
//            $transactions[] = array_merge($data, [
//                'transaction_date' => $transactionDate->copy()->format('Y-m-d')
//
//            ]);
//            $transactionDate->{$frequencyMap[$data['frequency']]}();
//        }

        return $transactions;
    }

}
