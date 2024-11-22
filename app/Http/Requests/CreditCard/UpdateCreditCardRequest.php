<?php

namespace App\Http\Requests\CreditCard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCreditCardRequest extends FormRequest
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
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('credit_card')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                })
            ],
            'bank_id' => 'sometimes|exists:banks,id',
            'issuer_id' => 'sometimes|exists:issuers,id',
            'limit' => 'sometimes|numeric|min:0',
        ];
    }
}
