<?php

namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Get purchase ID for unique rule (update)
        $id = $this->route('purchase') ?? $this->route('id') ?? $this->segment(3);

        return [
            'reference_no'       => 'required|string|max:50|unique:purchases,reference_no,' . $id,
            'purchase_date'      => 'required|date',
            'supplier_id'        => 'required|exists:suppliers,id',
            'store_id'           => 'required|exists:stores,id',
            'seller_id'          => 'required|exists:seller,id', // ប្តូរ users ទៅតាមឈ្មោះតារាងពិតប្រាកដ
            'status'             => 'required|in:Received,Pending,Ordered',
            'note'               => 'nullable|string|max:1000',
            'grand_total'        => 'required|numeric|min:0',

            'items'              => 'required|array|min:1',
            'items.*.productId'  => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.unitCost'   => 'required|numeric|min:0',
            'items.*.unitPrice'  => 'required|numeric|min:0',
            'items.*.discount'   => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Custom Validation Messages (Khmer)
     */
    public function messages(): array
    {
        return [
            'reference_no.required'       => 'សូមបញ្ចូលលេខវិក្កយបត្រ។',
            'reference_no.unique'         => 'លេខវិក្កយបត្រនេះមានរួចហើយក្នុងប្រព័ន្ធ។',
            'purchase_date.required'      => 'សូមបញ្ចូលកាលបរិច្ឆេទទិញ។',
            'supplier_id.required'        => 'សូមជ្រើសរើសអ្នកផ្គត់ផ្គង់។',
            'store_id.required'           => 'សូមជ្រើសរើសឃ្លាំង។',
            'seller_id.required'          => 'សូមជ្រើសរើសអ្នកលក់។',
            'status.required'             => 'សូមជ្រើសរើសស្ថានភាពទិញ។',
            'grand_total.required'        => 'សូមបញ្ចូលតម្លៃសរុប។',

            'items.required'              => 'សូមបញ្ចូលទំនិញយ៉ាងហោចណាស់មួយមុខ។',
            'items.*.productId.required'  => 'សូមជ្រើសរើសទំនិញ។',
            'items.*.qty.required'        => 'សូមបញ្ចូលបរិមាណទំនិញ។',
            'items.*.qty.integer'         => 'បរិមាណត្រូវតែជាចំនួនគត់។',
            'items.*.qty.min'             => 'បរិមាណត្រូវតែធំជាង ០។',
            'items.*.unitCost.required'   => 'សូមបញ្ចូលតម្លៃទិញមួយឯកតា។',
            'items.*.unitPrice.required'  => 'សូមបញ្ចូលតម្លៃលក់មួយឯកតា។',
        ];
    }
}


