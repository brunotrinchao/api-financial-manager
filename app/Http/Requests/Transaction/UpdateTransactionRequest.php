<?php

namespace App\Http\Requests\Transaction;

use App\Enums\FrequencyEnum;
use App\Enums\SourceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|exists:categories,id',
            'type' => 'sometimes|string|in:' . implode(',', TransactionTypeEnum::getValues()),
            'method' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'description' => 'sometimes|string',
            'transaction_date' => 'sometimes|date',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'source_type' => 'sometimes|string|in:' . implode(',', SourceTypeEnum::getValues()),
            'source_id' => 'sometimes|integer',
            'frequency' => 'sometimes|string|in:' . implode(',', FrequencyEnum::getValues()),
            'status' => 'sometimes|in:' . implode(',', TransactionStatusEnum::getValues()),
        ];
    }

    public function withValidator($validator){
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                $startDate = $this->input('start_date');
                $endDate = $this->input('end_date');
                $transactionDate = $this->input('transaction_date');

                if ($transactionDate < $startDate || $transactionDate > $endDate) {
                    $validator->errors()->add('transaction_date', 'The transaction date must be between the start and end dates.');
                }
            });
        }
    }
}
