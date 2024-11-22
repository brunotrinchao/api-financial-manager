<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBankRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255|unique:banks,name',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
