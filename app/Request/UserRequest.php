<?php
namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // public function rules(): array
    // {
    //     $userId = $this->route('id')??$this->route('user');

    //     $rules = [
    //         'name'     => 'required|string|max:255',
    //         // 'role'     => 'required|in:admin,user',
    //         'email'    => 'required|email|unique:users,email,' . $userId,
    //         'phone'    => 'nullable|string|max:20',
    //         'address'  => 'nullable|string|max:500',
    //     ];
    //     if ($this->isMethod('post')) {
    //     // បន្ថែម confirmed: វានឹងផ្ទៀងផ្ទាត់ជាមួយ password_confirmation ដោយស្វ័យប្រវត្តិ
    //     $rules['password'] = 'required|string|min:8|confirmed';
    //     } else {
    //         $rules['password'] = 'nullable|string|min:8|confirmed';
    //     }

    //     return $rules;
    // }
    // public function rules(): array
    // {
    //     // ១. ប្រសិនបើជាការ Login (ឆែកតាមឈ្មោះ Route ឬ URL)
    //     if ($this->is('login*') || $this->routeIs('login.post')) {
    //         return [
    //             'email'    => 'required|email',
    //             'password' => 'required|string',
    //         ];
    //     }

    //     // ២. ប្រសិនបើជាការ Create ឬ Update User (កូដចាស់របស់បង)
    //     $userId = $this->route('id') ?? $this->route('user');

    //     $rules = [
    //         'name'    => 'required|string|max:255',
    //         'email'   => 'required|email|unique:users,email,' . ($userId ?? 'NULL'),
    //         'phone'   => 'nullable|string|max:20',
    //         'address' => 'nullable|string|max:500',
    //     ];

    //     if ($this->isMethod('post')) {
    //         $rules['password'] = 'required|string|min:8|confirmed';
    //     } else {
    //         $rules['password'] = 'nullable|string|min:8|confirmed';
    //     }

    //     return $rules;
    // }

    public function rules(): array
    {
        // ១. ប្រសិនបើជាការ Login
        if ($this->is('login*') || $this->routeIs('login.post')) {
            return [
                'email'    => 'required|email',
                'password' => 'required|string',
            ];
        }

        // ២. ប្រសិនបើជាការ Create ឬ Update User
        $userId = $this->route('id') ?? $this->route('user');

        $rules = [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . ($userId ?? 'NULL'),
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean', // បន្ថែមការឆែក status

            // បន្ថែមការ Validate role_id ឱ្យមានពិតប្រាកដក្នុង table roles
            'role_id'   => 'required|exists:roles,id',
            'profile_picture' => [
                    'nullable',           // មិនបង្ខំថាត្រូវតែមានរូបទេ
                    'image',              // ត្រូវតែជាប្រភេទរូបភាព (jpg, jpeg, png, bmp, gif, svg, webp)
                    'mimes:jpeg,png,jpg', // កំណត់ប្រភេទ extension ឱ្យច្បាស់លាស់
                    'max:2048',           // កំណត់ទំហំអតិបរមា 2MB (2048 KB)
                ],
        ];

        // ឆែក Password សម្រាប់ Create (Required) និង Update (Optional)
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'សូមបញ្ចូលឈ្មោះពេញរបស់អ្នក',
            'email.required'    => 'សូមបញ្ចូលអាសយដ្ឋានអ៊ីមែល',
            'email.unique'      => 'អ៊ីមែលនេះមានអ្នកប្រើប្រាស់រួចហើយ',
            'password.required' => 'សូមបញ្ចូលលេខសម្ងាត់',
            'password.min'      => 'លេខសម្ងាត់ត្រូវមានយ៉ាងតិច ៦ ខ្ទង់',
            'role.required'     => 'សូមជ្រើសរើសតួនាទី',
            'password.confirmed' => 'ការបញ្ជាក់លេខសម្ងាត់មិនត្រឹមត្រូវទេ (មិនដូចគ្នា)',
        ];
    }
}
