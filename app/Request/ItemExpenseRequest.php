<?php

namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;

class ItemExpenseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        // ចាប់យក ID ពី Route ដើម្បីដកចេញពីការពិនិត្យ Unique ពេល Update
        $id = $this->route('item_expense') ?? $this->route('id');

        return [
            'code'   => 'required|string|max:50|unique:item_expens,code,' . $id,
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function attributes(): array
    {
        return [
            'code'   => 'លេខកូដ',
            'name'   => 'ឈ្មោះទំនិញ/ចំណាយ',
            'status' => 'ស្ថានភាព',
        ];
    }


    public function messages(): array
    {
        return [
            'required' => 'សូមបញ្ចូល :attribute ជាចាំបាច់។',
            'unique'   => ':attribute នេះមានរួចហើយក្នុងប្រព័ន្ធ។',
            'in'       => ':attribute ដែលអ្នកជ្រើសរើសមិនត្រឹមត្រូវឡើយ។',
        ];
    }
}
