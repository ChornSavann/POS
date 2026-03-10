<?php
namespace App\Service\IService;

interface IPurchaseService {
    public function getAllPurchase();
    public function createPurchase(array $data); // ទទួល Array នៃ Purchase និង Items
    public function getById($id);
   // បន្ថែម Method ថ្មី
    public function updatePurchase($id, array $data);
    public function deletePurchase($id);
}
