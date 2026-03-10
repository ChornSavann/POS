<?php
namespace App\Request;
use Illuminate\Foundation\Http\FormRequest;
class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
       return [
            'name'        => 'required|string|max:255',
            'status'      => 'required|boolean',
            'description' => 'nullable|string', // ត្រូវតែមានបន្ទាត់នេះ ទើប validated() ចាប់យកវាទៅប្រើ
        ];
    }
}