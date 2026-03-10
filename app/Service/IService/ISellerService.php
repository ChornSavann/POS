<?php
namespace App\Service\IService;
use App\Models\Seller;
use App\Request\SellerRequest;
interface ISellerService
{
    public function getAllSeller();
    public function getByid($id);
    public function createSeller(SellerRequest $request);
    public function updateSeller(SellerRequest $request, $id);
    public function deleteSeller($id);
}