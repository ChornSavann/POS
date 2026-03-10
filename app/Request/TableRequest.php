<?php
namespace App\Request;
use Illuminate\Foundation\Http\FormRequest;

class TableRequest extends FormRequest{
   public function authorize()
{
    return true; // កុំភ្លេចដាក់ true
}

    public function rules()
    {
        // ទាញយក ID របស់តុពី Route ដើម្បីប្រាប់ថា "លើកលែង ID នេះមិនបាច់ឆែក unique"
        $tableId = $this->route('id');

        return [
            'name'   => 'required|string|max:255|unique:tables,name,' . $tableId,
            'status' => 'required|in:free,busy',
            'note'   => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required'   => 'សូមបញ្ចូលឈ្មោះតុ',
            'name.unique'     => 'ឈ្មោះតុនេះមានរួចហើយ',
            'status.required' => 'សូមជ្រើសរើសស្ថានភាពតុ',
            'status.in'       => 'ស្ថានភាពតុត្រូវតែជា ទំនេរ (free) ឬ ជាប់រវល់ (busy)',
        ];
    }
}

