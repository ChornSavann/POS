<?php

namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseTypeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        // ឆែកមើលថាតើជាការ Update ឬ Store ដើម្បីកំណត់ Unique Code
        $id = $this->route('expense_type')??$this->route('id'); // ទាញយក ID ពី Route parameter

        return [
            'code'      => 'required|string|max:50|unique:expense_types,code,' . $id,
            'amount'    => 'required|numeric|min:0',
            'expens_id' => 'required|exists:item_expens,id',
            'bank_id'   => 'required|exists:banks,id',
            'status'  => 'required|in:active,inactive',
        ];
    }


    public function attributes(): array
    {
        return [
            'code'      => 'Code',
            'amount'    => 'Amount',
            'expens_id' => 'Expense Category',
            'bank_id'   => 'Bank Account',
        ];
    }
}
