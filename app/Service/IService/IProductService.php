<?php
namespace App\Service\IService;
interface IProductService {
    public function getFormData(); // សម្រាប់ទាញយក Category, Brand, Unit ទៅដាក់ក្នុង Form
    public function getAllProducts();
    public function storeProduct(array $data);
    public function updateProduct($id, array $data);
    public function deleteProduct(array $ids);
    public function findProduct($id);
    public function getProductSuggestions(string $term);
    public function getLowStockProducts();
}
