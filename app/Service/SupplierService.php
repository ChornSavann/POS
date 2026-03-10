<?php
namespace App\Service;
use App\Repository\IRepository\ISupplierRepository;
use App\Service\IService\ISupplierService;
use App\Request\SupplierRequest;
class SupplierService implements ISupplierService
{
    protected $supplierRepository;

    public function __construct(ISupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function getAllSuppliers()
    {
        return $this->supplierRepository->all();
    }

    public function getSupplierById($id)
    {
        return $this->supplierRepository->find($id);
    }

    public function createSupplier(SupplierRequest $request)
    {
        return $this->supplierRepository->create($request->validated());
    }

    public function updateSupplier($id, SupplierRequest $request)
    {
        return $this->supplierRepository->update($id, $request->validated());
    }

    public function deleteSupplier($id)
    {
        return $this->supplierRepository->delete($id);
    }
}