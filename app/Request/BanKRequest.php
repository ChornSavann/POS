<?php

namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'bank_name'       => 'required|string|max:255',
            'account_name'    => 'required|string|max:255',
            'account_number'  => 'required|string|unique:banks,account_number,' . $this->route('id'),
            'current_balance' => 'nullable|numeric|min:0',
            'currency'        => 'required|string|max:10',
            'is_active'       => 'nullable|boolean',
        ];
    }

    /**
     * ប្ដូរឈ្មោះ Field សម្រាប់ការបង្ហាញ Error ជាភាសាខ្មែរ (Optional)
     */
    public function attributes(): array
    {
        return [
            'bank_name'      => 'ឈ្មោះធនាគារ',
            'account_name'   => 'ឈ្មោះម្ចាស់គណនី',
            'account_number' => 'លេខគណនី',
        ];
    }
}
