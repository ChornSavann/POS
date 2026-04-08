<?php

namespace App\Service;

use App\Models\Supplier;
use App\Repository\IRepository\IRepostRepository;
use App\Service\IService\IReportService;
use Carbon\Carbon;

class ReportService implements IReportService
{
    protected $reportRepo;
    public function __construct(IRepostRepository $reportRepo)
    {
        $this->reportRepo = $reportRepo;
    }

    public function getPurchaseReportData($filters)
    {
        return [
            // កែពី :: មកជា -> វិញ
            'purchases'    => $this->reportRepo->getPurchases($filters),
            'total_amount' => $this->reportRepo->getTotalAmount($filters),
            'suppliers'    => Supplier::all()
        ];
    }
    public function getDailyReportData($date)
    {
        $orders = $this->reportRepo->getDailyOrders($date);
        $summary = $this->reportRepo->getDailySummary($date);

        // គណនា Total Items (Qty) ចេញពី Collection $orders
        $totalItems = $orders->sum(function($order) {
            return $order->orderItems->sum('qty');
        });

        return array_merge($summary, [
            'orders'     => $orders,
            'totalItems' => $totalItems
        ]);
    }

    public function getReportDataStockAjustment(array $filters)
    {
        $adjustments = $this->reportRepo->getAdjustments($filters);
        return [
            'adjustments' => $adjustments,
            'totalIn'     => $adjustments->where('type', 'IN')->sum('qty'),
            'totalOut'    => $adjustments->where('type', 'OUT')->sum('qty'),
            'totalValue'  => $adjustments->sum(fn($item) =>
                (($item->type == 'IN' ? $item->product->cost : $item->product->price) ?? 0) * $item->qty
            )
        ];
    }

    public function getStockPerformanceReport(array $filters = [])
    {
        return [
            'top_products'  => $this->reportRepo->getProductPerformance(10, 'top', $filters),
            'slow_products' => $this->reportRepo->getProductPerformance(10, 'low', $filters),
        ];
    }

    public function getStockReportData($request)
    {
        $pageSize = $request->input('pageSize', 10);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $this->reportRepo->getStockInventoryQuery($startDate, $endDate);
        // ទាញយកទិន្នន័យទាំងអស់សម្រាប់គណនា Card (មុនពេល Paginate)
        $allProducts = $query->get();
        $stats = [
            'total_cost'  => $allProducts->sum(fn($p) => ($p->qty ?? 0) * $p->cost),
            'total_value' => $allProducts->sum(fn($p) => ($p->qty ?? 0) * $p->price),
            'low_stock'   => $allProducts->filter(fn($p) => ($p->qty ?? 0) <= $p->alert_qty)->count(),
        ];

        $products = $query->latest()->paginate($pageSize);

        return [
            'products' => $products,
            'stats'    => $stats
        ];
    }

    public function getSalesReportData($request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();

        $query = $this->reportRepo->getOrdersQuery(
            $startDate->startOfDay()->toDateTimeString(),
            $endDate->endOfDay()->toDateTimeString()
        );

        $orders = $query->orderByDesc('order_date')->get();

        // Revenue Chart Data
        $chartData = $orders->groupBy(fn($o) => Carbon::parse($o->order_date)->format('d M'))
            ->map(fn($group, $date) => [
                'date' => $date,
                'total' => $group->sum('grand_total'),
                'raw_date' => Carbon::parse($group->first()->order_date) // បន្ថែមសម្រាប់តម្រៀប
            ])
            ->sortBy('raw_date') // តម្រៀបពីចាស់ទៅថ្មី (ឆ្វេងទៅស្តាំ)
            ->values();

        // Payment Method Chart Data
        $paymentData = $orders->flatMap->payments
            ->groupBy(function ($p) {
                // ១. បើមាន Relationship ទៅកាន់ Bank ឱ្យយកឈ្មោះ Bank មកប្រើ
                if ($p->bank) {
                    return $p->bank->bank_name;
                }

                // ២. បើអត់មាន Bank (ID) ទេ តែមានឈ្មោះក្នុង payment_method ឱ្យយកឈ្មោះនោះ
                // ៣. បើ null ទាំងពីរ ឱ្យដាក់ថា CASH
                return $p->payment_method ?: 'CASH';
            })->map(function ($group, $method) {
                return [
                    'name'  => strtoupper($method), // ប្តូរជាអក្សរធំដើម្បីឱ្យស្អាតក្នុង Chart
                    'total' => (float)$group->sum('paid_amount')
                ];
            })->values();

        return [
            'orders'        => $orders,
            'chartData'     => $chartData->toJson(),
            'paymentData'   => $paymentData->toJson(),
            'totalSales'    => $orders->sum('grand_total'),
            'subTotal'      => $orders->sum('sub_total'),
            'totalDiscount' => $orders->sum('total_discount'),
            'startDate'     => $startDate->toDateString(),
            'endDate'       => $endDate->toDateString(),
        ];
    }

    public function getProfitLossData($year)
    {
        // រៀបចំ Sales By Category
        $salesData = $this->reportRepo->getSalesByCategory($year);
        $salesByCategory = $salesData->groupBy('cat_name')->map(function ($items) {
            $monthlyValues = array_fill(1, 12, 0);
            foreach ($items as $item) {
                $monthlyValues[$item->month] = (float)$item->total;
            }
            return $monthlyValues;
        });

        $monthlySales = $this->reportRepo->getMonthlySales($year);
        $monthlyCOGS = $this->reportRepo->getMonthlyCOGS($year);
        $monthlyExpenses = $this->reportRepo->getMonthlyExpenses($year);

        // បំពេញខែដែលអត់មាន Data
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[$i] = $monthlySales[$i] ?? 0;
            $monthlyCOGS[$i] = $monthlyCOGS[$i] ?? 0;
            $monthlyExpenses[$i] = $monthlyExpenses[$i] ?? 0;
        }

        return [
            'salesByCategory' => $salesByCategory,
            'monthlySales' => $monthlySales,
            'monthlyCOGS' => $monthlyCOGS,
            'monthlyExpenses' => $monthlyExpenses,
        ];
    }

   public function getProductPerformanceReport($startDate, $endDate)
   {
        $data = $this->reportRepo->getProductPerformances($startDate, $endDate);

        return [
            // លក់ដាច់បំផុត (Top 10)
            'topSelling' => $data->sortByDesc('total_qty')->take(10),

            // លក់មិនដាច់ (Bottom 10)
            'slowMoving' => $data->sortBy('total_qty')->take(10),

            // ទិន្នន័យសរុបសម្រាប់ Dashboard Cards
            'totalItems' => $data->count(),
            'totalQty'   => $data->sum('total_qty'),
            'totalSales' => $data->sum('total_revenue')
        ];
    }

    public function getMonthlyPerformance($year)
    {
        $monthlyData = $this->reportRepo->getMonthlySalesReport($year);
        // Initialize 12 months with zero values
        $report = collect(range(1, 12))->map(function($month) use ($monthlyData) {
            $data = $monthlyData->where('month', $month)->first();
            return [
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'revenue'    => $data ? $data->revenue : 0,
                'orders'     => $data ? $data->total_orders : 0,
            ];
        });

        return [
            'monthly_stats' => $report,
            'yearly_total'  => $report->sum('revenue'),
            'avg_monthly'   => $report->avg('revenue')
        ];
    }

    public function getMonthlyDetailsData($month, $year)
    {
        $invoices = $this->reportRepo->getMonthlyInvoiceDetails($month, $year);
        $monthName = date('F', mktime(0, 0, 0, $month, 1));
        return [
            'invoices'  => $invoices,
            'monthName' => $monthName,
            'year'      => $year
        ];
    }
}
