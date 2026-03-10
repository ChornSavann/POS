<?php
namespace App\Repository;
use App\Models\Seller;
use App\Request\SellerRequest;
use App\Repository\IRepository\ISellerRepository;
class SellerRepository implements ISellerRepository
{
    public function all()
    {
        return Seller::all();
    }

    public function find($id)
    {
        return Seller::findOrFail($id);
    }

    public function create(SellerRequest $request)
    {
        return Seller::create($request->validated());
    }

    public function update(SellerRequest $request, $id)
    {
        $seller = Seller::findOrFail($id);
        $seller->update($request->validated());
        return $seller;
    }

    public function delete($id)
    {
        $seller = Seller::findOrFail($id);
        return $seller->delete();
    }
}