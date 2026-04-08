<?php
namespace App\Repository\IRepository;
interface IRepostRepository{
    public function getPurchases($filters, $perPage = 20);
    public function getTotalAmount($filters);
    public function getDailyOrders($date);
    public function getDailySummary($date);
    public function getAdjustments(array $filters);
    public function getProductPerformance($limit = 10, $type = 'top');
    public function getStockInventoryQuery($startDate = null, $endDate = null);
    public function getOrdersQuery($startDate, $endDate);

    public function getSalesByCategory($year);
    public function getMonthlySales($year);
    public function getMonthlyCOGS($year);
    public function getMonthlyExpenses($year);

     public function getProductPerformances($startDate, $endDate);
      public function getMonthlySalesReport($year);
      public function getMonthlyInvoiceDetails($month, $year);
}
