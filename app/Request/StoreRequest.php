<?php
namespace App\Request;
use Illuminate\Foundation\Http\FormRequest;
class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // អនុញ្ញាតឲ្យសំណើរ ត្រូវបានអនុញ្ញាត
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            // 'slug' => 'required|string|max:255|unique:stores,slug,' . $this->route('id'),
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|boolean',
        ];
    }
}