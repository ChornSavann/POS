<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\IService\ISupplierService;
use App\Request\SupplierRequest;

class SupplierController extends Controller
{
    private $supplierService;

    public function __construct(ISupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }
    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();
       return view('suppliers.index', compact('suppliers'));
    }
    public function create()
    {
        return view('suppliers.create');
    }
    public function store(SupplierRequest $request)
    {        $this->supplierService->createSupplier($request);
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }
    public function edit($id)
    {
        $supplier = $this->supplierService->getSupplierById($id);
        return view('suppliers.edit', compact('supplier'));
    }
    public function update(SupplierRequest $request, $id)
    {
        $this->supplierService->updateSupplier($id, $request);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }
    public function destroy($id)
    {
        $this->supplierService->deleteSupplier($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
