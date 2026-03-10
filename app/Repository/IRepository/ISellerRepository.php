<?php
namespace App\Repository\IRepository;
use App\Models\Seller;
use App\Request\SellerRequest;
interface ISellerRepository
{
    public function all();
    public function find($id);
    public function create(SellerRequest $request);
    public function update(SellerRequest $request, $id);
    public function delete($id);
}