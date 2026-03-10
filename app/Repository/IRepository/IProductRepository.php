<?php
namespace App\Repository\IRepository;

interface IProductRepository {
    public function getUnit();
    public function getCategory();
    public function getBrand();
    public function getStock();
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function searchSuggestions(string $term);
    public function  getLowStockProducts();
}
