<?php

namespace App\Http\Requests\Transaction;

use App\Enums\FrequencyEnum;
use App\Enums\SourceTypeEnum;
use App\Enums\TransactionTypeEnum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
                'required_if:type,income|in:' . implode(',', SourceTypeEnum::getValues())
            ],
            'frequency' => [
                'required_if:type,expense|in:' . implode(',', FrequencyEnum::getValues()),
                function ($attribute, $value, $fail) {
                    if ($this->interval > 1 && $this->type === 'expense' && is_null($value)) {
                        $fail('O campo "Frequência" é obrigatório para despesas recorrentes.');
                    }
                }
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
            $transactions[] = array_merge($data, [
                'transaction_date' => $transactionDate->copy()->format('Y-m-d')

            ]);
            $transactionDate->{$frequencyMap[$data['frequency']]}();
        }

        return $transactions;
    }

}
