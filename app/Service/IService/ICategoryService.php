<?php
namespace App\Service\IService;

interface ICategoryService {
    public function getAllCategories();
    public function getCategoryDetails($id);
    public function storeCategory(array $data);
    public function editCategory($id, array $data);
    public function removeCategory($id);
}