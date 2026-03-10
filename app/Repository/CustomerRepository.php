<?php
namespace App\Repository;
use App\Models\Customer;
use App\Repository\IRepository\ICustomerRepository;
class CustomerRepository implements ICustomerRepository
{
    // ឧទាហរណ៍ក្នុង Repository
    public function all($perPage = 10)
    {
        $search = request('search');
        
        return Customer::when($search, function($query) use ($search) {
            $query->where('customer_code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate($perPage)
        ->withQueryString(); // សំខាន់ណាស់ ដើម្បីកុំឱ្យបាត់ Search Keyword ពេលចុចប្តូរទំព័រ (Pagination)
    }

    public function find($id)
    {
        return Customer::findOrFail($id);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update($id, array $data)
    {
        $customer = $this->find($id);
        $customer->update($data);
        return $customer;
    }

    public function delete($id)
    {
        $customer = $this->find($id);
        return $customer->delete();
    }
}