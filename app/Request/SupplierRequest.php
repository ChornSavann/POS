<?php
namespace App\Request;
use Illuminate\Foundation\Http\FormRequest;
class SupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'gender'    => 'required|in:male,female',
            'email' => 'required|email|unique:suppliers,email,' . $this->route('id'),
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ];
    }
}