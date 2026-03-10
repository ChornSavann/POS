<?php
namespace App\Request;
use Illuminate\Foundation\Http\FormRequest;
class SellerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // អនុញ្ញាតឱ្យគ្រប់គ្នាអាចធ្វើសំណើនេះបាន (ប្ដូរតាមតម្រូវការរបស់អ្នក)
    }

   
    public function rules(): array
    {
        $sellerid = $this->route('id'); // ដើម្បីទទួលបាន ID របស់ Seller បច្ចុប្បន្ន (សម្រាប់ការត្រួតពិនិត្យ unique)
        return [
            'name' => 'required|string|max:255',    
            'gender' => 'nullable|in:ប្រុស,ស្រី,ផ្សេងៗ', // ប្រើ enum សម្រាប់ជម្រើសច្បាស់លាស់
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:seller,email,' . $sellerid, // unique តាម email ហើយមិនរំលង record បច្ចុប្បន្ន
            'address' => 'nullable|string',
            'status' => 'required|boolean',
        ];
    }
}