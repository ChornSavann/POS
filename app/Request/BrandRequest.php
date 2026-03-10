<?php

namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;
// កែឈ្មោះត្រង់នេះពី UnitRequest មកជា BrandRequest វិញ
class BrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
       return [
            'name'        => 'required|string|max:255',
            // សម្រាប់ Brand លោកអ្នកប្រហែលជាចង់ generate slug ស្វ័យប្រវត្តិ ឬអាចដក slug ចេញបើមិនទាន់ប្រើ
            'status'      => 'required|boolean',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // បន្ថែមការឆែករូបភាព
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'សូមបញ្ចូលឈ្មោះម៉ាកយីហោ (Brand)!',
            'name.unique'   => 'ឈ្មោះម៉ាកយីហោនេះមានរួចហើយ!',
            'status.required' => 'សូមជ្រើសរើសស្ថានភាព!',
            'image.image'   => 'ឯកសារត្រូវតែជារូបភាព!',
            'image.mimes'   => 'រូបភាពត្រូវតែជាប្រភេទ: jpeg, png, jpg, gif!',
            'image.max'     => 'រូបភាពមិនត្រូវមានទំហំលើសពី 2MB ឡើយ!',
        ];
    }
}
