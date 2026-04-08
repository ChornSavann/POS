<?php
namespace App\Repository\IRepository;

interface IOrderRepository {
    public function getLastInvoiceNo();
    public function getAllProducts();
    public function getAllCustomers();
    public function getAllSellers();
    public function getAllTables();
    public function getAllCategories();
    public function getBank();
    public function createOrder(array $data);
    public function createOrderItem(array $data);
    public function updateProductStock($productId, $qty);
    public function updateTableStatus($tableId, $status); // ថែមមុខងារប្តូរពណ៌តុ
    public function createPayment(array $data);
    public function getAllOrders($pageSize);
    public function getTotalSales();
    public function getTotalDebt();
    public function getOrderForPrint($id);
    public function getAllOrdersForPrint();
    public function getOrderForInvoice($id);
    public function getShopSetting();

}
