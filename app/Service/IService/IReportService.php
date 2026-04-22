<?php
namespace App\Service\IService;
interface IReportService{

    public function getPurchaseReportData($filters);
    public function getDailyReportData($date);
    public function getReportDataStockAjustment(array $filters);
    public function getStockPerformanceReport();
    public function getStockReportData($request);
    public function getSalesReportData($request);
    // public function getProfitLossData($year);
    public function getProductPerformanceReport($startDate, $endDate) ;
    public function getMonthlyPerformance($year);
    public function getMonthlyDetailsData($month, $year);
    public function getProfitLossData(int $year): array;
    public function getInvoiceData(int $id): array;
}
