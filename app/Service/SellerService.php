<?php
namespace App\Service;
use App\Models\Seller;
use App\Request\SellerRequest;
use App\Repository\IRepository\ISellerRepository;
use App\Service\IService\ISellerService;
class SellerService implements ISellerService
{
    protected $sellerRepository;

    public function __construct(ISellerRepository $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
    }

    public function getAllSeller()
    {
        return $this->sellerRepository->all();
    }

    public function getByid($id)
    {
        return $this->sellerRepository->find($id);
    }

    public function createSeller(SellerRequest $request)
    {
        return $this->sellerRepository->create($request);
    }

    public function updateSeller(SellerRequest $request, $id)
    {
        return $this->sellerRepository->update($request, $id);
    }

    public function deleteSeller($id)
    {
        return $this->sellerRepository->delete($id);
    }
}