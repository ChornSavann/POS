<?php

namespace App\Http\Controllers;

use App\Models\CashSession;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockMovement;
use Carbon\Carbon;
use App\Models\Stores;
use App\Models\Supplier;
use App\Service\IService\IReportService;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\ExpenseType;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
   protected $reportService;
   public function __construct(IReportService $reportService)
   {
        $this->reportService=$reportService;
   }

    // public function printInvoice($id)
    // {
    //     $storee = Stores::first();
    //     $order = Order::with(['customer', 'orderItems.product', 'seller'])->findOrFail($id);
    //     $cashierName =Auth::user()->name ?? 'Admin';
    //     $rate = 4100; // អត្រាប្តូរប្រាក់សម្រាប់បង្ហាញក្នុងវិក្កយបត្រ
    //     return view('order.invoice_sale', compact('order', 'storee', 'cashierName', 'rate'));
    // }
    public function printInvoice(int $id)
    {
        $data = $this->reportService->getInvoiceData($id);
        return view('order.invoice_sale', $data);
    }


    public function daily()
    {
        $today = now()->toDateString();
        $data = $this->reportService->getDailyReportData($today);

        return view('report.daily', [
            'orders'        => $data['orders'],
            'totalOrders'   => $data['total_orders'],
            'totalItems'    => $data['totalItems'],
            'totalDiscount' => $data['total_discount'],
            'totalSales'    => $data['total_sales'],
            'netSales'      => $data['net_sales'],
        ]);
    }

    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.product'])->findOrFail($id);
        return response()->json($purchase);
    }

    public function index(Request $request)
    {
        $data = $this->reportService->getPurchaseReportData($request->all());

        return view('report.purchase_report', [
            'purchases' => $data['purchases'],
            'suppliers' => $data['suppliers'],
            'total_amount' => $data['total_amount']
        ]);
    }


    public function stockAdjustmentReport(Request $request) {
        $data = $this->reportService->getReportDataStockAjustment($request->all());
        return view('report.stock_adjustment', $data);
    }


    public function performanceReport(Request $request)
    {
        $data = $this->reportService->getStockPerformanceReport($request->all());
        return view('report.performance', $data);
    }


    public function stockInventory(Request $request)
    {
        $data = $this->reportService->getStockReportData($request);

        return view('report.stock_inventory', [
            'products' => $data['products'],
            'stats'    => $data['stats']
        ]);
    }

    public function salesReport(Request $request)
    {
        $data = $this->reportService->getSalesReportData($request);
        return view('report.sales', $data);
    }

    public function getNetProfitReport()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // ១. គណនាចំណូលសរុប និងថ្លៃដើមសរុបពី OrderItems (សន្មតថាបងមាន cost_price ក្នុង Table)
        $salesData = OrderItem::whereHas('order', function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('status', 'completed'); // គិតតែវិក្កយបត្រដែលជោគជ័យ
            })
            ->select(
                DB::raw('SUM(total) as total_revenue'),
                // បើបងមិនទាន់មាន cost_price ក្នុង order_items ទេ បងត្រូវ Join ជាមួយ table products ដើម្បីទាញយកតម្លៃដើម
                DB::raw('SUM(qty * cost_price) as total_cost')
            )
            ->first();

        // ២. គណនាការចំណាយសរុប (Operating Expenses)
        $totalExpense = ExpenseType::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'active') // គិតតែការចំណាយដែល Verify រួច
            ->sum('amount');

        // ៣. គណនាចំណេញសុទ្ធ
        $revenue = $salesData->total_revenue ?? 0;
        $costOfGoods = $salesData->total_cost ?? 0;
        $grossProfit = $revenue - $costOfGoods;
        $netProfit = $grossProfit - $totalExpense;

        return [
            'revenue' => $revenue,
            'cogs' => $costOfGoods,
            'expense' => $totalExpense,
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit
        ];
    }


    public function productPerformanceReport(Request $request) {
        $startDate = $request->startDate ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->endDate   ?? Carbon::now()->format('Y-m-d');

        $report = $this->reportService->getProductPerformanceReport($startDate, $endDate);

        return view('report.product_performance', array_merge($report, [
            'startDate' => $startDate,
            'endDate'   => $endDate
        ]));
    }

    public function monthlySalesReport(Request $request)
    {
        // 1. Get current year or year from request (Standard Default)
        $year = $request->input('year', Carbon::now()->year);

        // 2. Fetch processed data from Service
        $data = $this->reportService->getMonthlyPerformance($year);

        // 3. Return view with clear variable names
        return view('report.monthly', [
            'monthly_stats' => $data['monthly_stats'],
            'yearly_total'  => $data['yearly_total'],
            'avg_monthly'   => $data['avg_monthly'],
            'year'          => $year,
            'years_list'    => range(Carbon::now()->year, Carbon::now()->year - 5) // For filter dropdown
        ]);
    }


    public function monthlyDetails($month, $year)
    {
        $data = $this->reportService->getMonthlyDetailsData($month, $year);
        return view('report.monthly_invoices', $data);
    }


    public function getWeeklyReport(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        $day = $request->get('day');

        // ១. ទាញយក Invoices (សម្រាប់បង្ហាញ List លម្អិត)
        $invoices = Order::whereYear('order_date', $year)
            ->where('is_completed', '!=', 2) // ចម្រោះដក Cancel ចេញ
            ->when($month, function($q) use ($month) { $q->whereMonth('order_date', $month); })
            ->when($day, function($q) use ($day) { $q->whereDay('order_date', $day); })
            ->withSum('orderItems as total_quantity_sold', 'qty')
            ->get();

        // ២. Subquery សម្រាប់បូកសរុបចំនួនទំនិញ (Qty) ប្រចាំថ្ងៃ
        $itemSubquery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.is_completed', '!=', 2) // មិនបូកបញ្ចូល Qty របស់ Order ដែល Cancel
            ->select(
                DB::raw('DATE(orders.order_date) as item_date'),
                DB::raw('SUM(order_items.qty) as daily_qty')
            )
            ->groupBy(DB::raw('DATE(orders.order_date)'));

        // ៣. ទាញយករបាយការណ៍សរុបតាមសប្តាហ៍/ថ្ងៃ
        $reports = Order::select([
                DB::raw('WEEK(orders.order_date, 1) as week_number'),
                DB::raw('DAYNAME(orders.order_date) as day_name'),
                DB::raw('DATE(orders.order_date) as exact_date'),
                DB::raw('COUNT(orders.id) as total_invoices'),
                DB::raw('SUM(orders.grand_total) as total_amount'),
                'items.daily_qty as total_qty'
            ])
            ->leftJoinSub($itemSubquery, 'items', function ($join) {
                $join->on(DB::raw('DATE(orders.order_date)'), '=', 'items.item_date');
            })
            ->whereYear('orders.order_date', $year)
            ->where('orders.is_completed', '!=', 2) // មិនបូកបញ្ចូលចំនួនទឹកប្រាក់ដែល Cancel
            ->when($month, function($q) use ($month) { $q->whereMonth('orders.order_date', $month); })
            ->when($day, function($q) use ($day) { $q->whereDay('orders.order_date', $day); })
            ->groupBy(
                DB::raw('WEEK(orders.order_date, 1)'),
                DB::raw('DAYNAME(orders.order_date)'),
                DB::raw('DATE(orders.order_date)'),
                'items.daily_qty'
            )
            ->orderBy(DB::raw('DATE(orders.order_date)'), 'asc')
            ->get();

        return view('report.weekly', compact('reports', 'invoices', 'year', 'month', 'day'));
    }

    public function profitLossReport(Request $request) {
        $year = $request->year ?? date('Y');
        $startDate = $request->startDate ?? "$year-01-01";
        $endDate = $request->endDate ?? date('Y-m-d');

        // ១. រៀបចំឈ្មោះខែ
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = date('M', mktime(0, 0, 0, $m, 1));
        }

        // ២. ទាញទិន្នន័យតាមរយៈ Service
        $data = $this->reportService->getProfitLossData($year);

        return view('report.profit_loss', array_merge($data, [
            'year' => $year,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'months' => $months
        ]));
    }


    public function profitLossReports(Request $request) {
        $year = $request->year ?? date('Y');
        $startDate = $request->startDate ?? "$year-01-01";
        $endDate = $request->endDate ?? date('Y-m-d');

        // ១. រៀបចំឈ្មោះខែ
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = date('M', mktime(0, 0, 0, $m, 1));
        }

        // ២. ទាញទិន្នន័យតាមរយៈ Service
        $data = $this->reportService->getProfitLossData($year);

        return view('report.profit', array_merge($data, [
            'year' => $year,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'months' => $months
        ]));
    }

}
