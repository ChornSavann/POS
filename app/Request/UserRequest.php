<?php
namespace App\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       
        $userId = $this->route('user'); 

        $rules = [
            'name'  => 'required|string|max:255',
            'role'  => 'required|in:admin,user',
            'email' => 'required|email|unique:users,email,' . $userId,
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        return $rules;
    }
}