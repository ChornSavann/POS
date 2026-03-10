<?php
namespace App\Request;
use Illuminate\Foundation\Http\FormRequest;
class CustomerRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true; // អនុញ្ញាតឱ្យគ្រប់គ្នាអាចធ្វើសំណើនេះបាន
    }

    public function rules(): array
    {
        // ចាប់យក ID ពី Route (ប្តូរពី 'customer' មក 'id' តាម Controller របស់បង)
        $customerId = $this->route('id'); 

        return [
            'name' => 'required|string|max:255',
            'customer_code' => 'required|string|max:255|unique:customers,customer_code,' . $customerId,
            'email' => 'required|email|max:255|unique:customers,email,' . $customerId,
            'zone' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];
    }
}