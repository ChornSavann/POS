<?php
namespace App\Request;
use Illuminate\Foundation\Http\FormRequest;
class UnitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules(): array
{
    return [
        'name'           => 'required|string|max:255',
        'baseunit_id'    => 'nullable|exists:units,id', // ឆែកមើលថាមាន ID ក្នុង table units មែនអត់
        'operator'       => 'nullable|string|in:*,/',   // អនុញ្ញាតតែសញ្ញា * និង /
        'operator_value' => 'nullable|numeric|min:0',   // ត្រូវតែជាលេខ
        'note'           => 'nullable|string',
    ];
}
}