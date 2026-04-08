<?php

namespace App\Request; // ពិនិត្យ Namespace ឱ្យត្រូវតាម Standard របស់ Laravel

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        // ចាប់យក ID ពី Route ដើម្បីប្រើក្នុង unique ignore ពេល update
        $productId = $this->route('id') ?? $this->route('Product');
        return [
            'term' => 'nullable|string',
            'name'        => 'required|string|max:255',
            'barcode'     => 'required|string|max:100|unique:products,barcode,' . $productId,
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'required|exists:brands,id',
            'unit_id'     => 'required|exists:units,id',
            'cost'        => 'required|numeric|min:0', // ✅ OK
            'sale_unit_id'=> 'required',
            'purchase_unit_id' => 'required',
            'price'       => 'required|numeric|gt:0',
            'alert_qty'   => 'nullable|integer|min:0',
            'status'      => 'required|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'address'     => 'nullable|string|max:500',
        ];
    }


    public function attributes(): array
    {
        return [
            'name' => 'ឈ្មោះទំនិញ',
            'barcode' => 'បាកូដ',
            'category_id' => 'ប្រភេទទំនិញ',
            'unit_id' => 'ខ្នាត',
            'cost' => 'តម្លៃដើម',
            'price' => 'តម្លៃលក់',
        ];
    }
}
