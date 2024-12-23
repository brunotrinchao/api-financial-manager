<?php

namespace App\Http\Requests\CreditCard;

use App\Enums\FlagsCreditcardsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCreditCardRequest extends FormRequest
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
                'required',
                'string',
                'max:255',
                'unique:credit_cards,name,NULL,id,user_id,' . auth()->id()
            ],
            'issuer_id' => [
                'required|exists:issuers,id',
                'string'
            ],
            'limit' => 'required|numeric|min:0',
        ];
    }
}
