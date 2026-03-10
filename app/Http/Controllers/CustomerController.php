<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\IService\ICustomerService;
use App\Request\CustomerRequest;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(ICustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        $customers = $this->customerService->getallCustomer();
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function edit($id)
    {
        $customer = $this->customerService->getByid($id);
        return view('customer.edit', compact('customer'));
    }

    public function store(CustomerRequest $request)
    {
        $this->customerService->createCustomer($request->validated());
        return redirect()->route('customer.index')
                        ->with('success', 'អតិថិជនថ្មីត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    public function update(CustomerRequest $request, $id)
    {
        $customer = $this->customerService->updateCustomer($id, $request->validated());
        return redirect()->route('customer.index')
                        ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $this->customerService->deleteCustomer($id);
        return redirect()->route('customer.index')->with('success', 'Customer deleted successfully.');
    }
}
