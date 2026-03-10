<?php
namespace App\Service\IService;
interface IBrandService {
    public function getAllBrands($request);
    public function getBrandDetails($id);
    public function storeBrand(array $data);
    public function updateBrand($id, array $data);
    public function removeBrand($id);
}