<?php
namespace App\Service\IService;
use App\Request\SupplierRequest;
interface ISupplierService
{
    public function getAllSuppliers();
    public function getSupplierById($id);
    public function createSupplier(SupplierRequest $request);
    public function updateSupplier($id, SupplierRequest $request);
    public function deleteSupplier($id);
}