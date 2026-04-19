<?php
namespace App\Repository\IRepository;
use Illuminate\Support\Collection;
interface IRepostRepository{
    public function getPurchases($filters, $perPage = 20);
    public function getTotalAmount($filters);
    public function getDailyOrders($date);
    public function getDailySummary($date);
    public function getAdjustments(array $filters);
    public function getProductPerformance($limit = 10, $type = 'top');
    public function getStockInventoryQuery($startDate = null, $endDate = null);
    public function getOrdersQuery($startDate, $endDate);

    public function getProductPerformances($startDate, $endDate);
    public function getMonthlySalesReport($year);
    public function getMonthlyInvoiceDetails($month, $year);

    /// New methods for inventory and sales analysis
    public function getSalesByCategory(int $year): Collection;
    public function getMonthlySales(int $year): array;
    public function getMonthlyCOGS(int $year): array;
    public function getMonthlyExpenses(int $year): array;
    public function getMonthlyPurchaseCost(int $year): array;
    public function getTopProducts(int $year, int $limit = 10): Collection;
    public function getAvailableYears(): array;
}
