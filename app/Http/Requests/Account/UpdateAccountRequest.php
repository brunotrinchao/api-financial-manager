<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $account = $this->route('account');
        return $account && $this->user()->id === $account->user_id;
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
                Rule::unique('accounts')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                }),
            ],
            'bank_id' => 'sometimes|exists:banks,id',
            'balance' => 'sometimes|numeric|min:0',
        ];
    }
}
