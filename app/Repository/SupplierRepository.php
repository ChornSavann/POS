<?php
namespace App\Repository;
use App\Models\Supplier;
use App\Repository\IRepository\ISupplierRepository;
class SupplierRepository implements ISupplierRepository
{
    public function all()
    {
        return Supplier::all();
    }

    public function find($id)
    {
        return Supplier::find($id);
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function update($id, array $data)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->update($data);
            return $supplier;
        }
        return null;
    }

    public function delete($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->delete();
            return true;
        }
        return false;
    }
}