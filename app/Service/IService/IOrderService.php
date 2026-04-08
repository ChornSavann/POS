<?php
namespace App\Service\IService;

interface IOrderService {
    public function getProducts();
    public function getSellers();
    public function getCustomers();
    public function getCategories();
    public function getBank();
    public function getTables();
    public function generateInvoice(array $orderData, array $cartItems);
    public function processCheckOut(array $data);
    public function payDebt(array $data);
    public function getListOrderData($request);
    public function changeTableStatus($tableId, $status);
    public function getPrintData($id);
   public function getDataForPrint();
   //
   public function generateKHQR($grandTotal, $exchangeRate = 4100);
    public function getInvoiceData($id);
}
