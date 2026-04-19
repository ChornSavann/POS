<?php
namespace App\Repository;

use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Repository\IRepository\IRepostRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class RepostRepository implements IRepostRepository{

    public function getPurchases($filters, $perPage = 20)
    {
        return $this->applyFilters($filters)
            ->with(['supplier', 'items'])
            ->orderBy('purchase_date', 'desc')
            ->paginate($perPage);
    }

    public function getTotalAmount($filters) {
        return $this->applyFilters($filters)->sum('grand_total');
    }

    private function applyFilters($filters) {
        $query = Purchase::query();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('purchase_date', [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay()
            ]);
        }

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['reference_no'])) {
            $query->where('reference_no', 'LIKE', '%' . $filters['reference_no'] . '%');
        }

        return $query;
    }

    public function getDailyOrders($date) {
        return Order::with('orderItems')
            ->whereDate('order_date', $date)
            ->get();
    }

    public function getDailySummary($date) {
        $query = Order::whereDate('order_date', $date);

        return [
            'total_orders'   => $query->count(),
            'total_discount' => $query->sum('total_discount'),
            'total_sales'    => $query->sum('sub_total'),
            'net_sales'      => $query->sum('grand_total'),
        ];
    }

    public function getAdjustments(array $filters) {
        $query = StockMovement::with('product');

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }
        if (!empty($filters['search'])) {
            $query->whereHas('product', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            });
        }
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
    // app/Repositories/ProductReportRepository.php
    public function getProductPerformance($limit = 10, $type = 'top', array $filters = [])
    {
       // ក្នុង RepostRepository.php
        $query = Product::with(['category'])
            ->withSum(['orderItems as total_sold' => function($q) use ($filters) {
                if (!empty($filters['start_date'])) {
                    $q->whereDate('created_at', '>=', $filters['start_date']);
                }
                if (!empty($filters['end_date'])) {
                    $q->whereDate('created_at', '<=', $filters['end_date']);
                }
            }], 'qty'); // <--- ប្តូរពី 'quantity' មកជា 'qty' (ឬឈ្មោះ Column ដែលបងមានក្នុង DB)

               if ($type == 'top') {
                // លក់ដាច់បំផុត៖ រៀបពីច្រើនទៅតិច
                return $query->whereHas('orderItems')
                            ->orderBy('total_sold', 'desc')
                            ->take($limit)
                            ->get();
                } else {
                    // លក់យឺត៖ រៀបពីតិចទៅច្រើន ដោយរុញ NULL (មិនទាន់លក់ដាច់សោះ) ទៅលើគេ
                    return $query->orderByRaw('total_sold IS NULL DESC') // បើ NULL ឱ្យមកលើគេ
                                ->orderBy('total_sold', 'asc')        // បន្ទាប់មកតម្រៀបពីតិចទៅច្រើន
                                ->take($limit)
                                ->get();
                }
    }

    public function getStockInventoryQuery($startDate = null, $endDate = null)
    {
        $query = Product::with(['category'])
            ->withSum('stocks as qty', 'qty');

        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }

    public function getOrdersQuery($startDate, $endDate)
    {
        return Order::with(['customer', 'payments'])
            ->whereBetween('order_date', [$startDate, $endDate]);
    }



    public function getProductPerformances($startDate, $endDate)
    {
        return DB::table('products')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) use ($startDate, $endDate) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->whereBetween('orders.order_date', [$startDate, $endDate])
                     ->where('orders.is_completed', 1);
            })
            ->select(
                'products.name',
                'products.price',
                DB::raw('SUM(IFNULL(order_items.qty, 0)) as total_qty'),
                DB::raw('SUM(IFNULL(order_items.qty * order_items.price, 0)) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->get();
    }

    public function getMonthlySalesReport($year)
    {
        return DB::table('orders')
            ->whereYear('order_date', $year)
            ->where('is_completed', 1)
            ->select(
                DB::raw('MONTH(order_date) as month'),
                DB::raw('SUM(grand_total) as revenue'),
                DB::raw('COUNT(id) as total_orders')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getMonthlyInvoiceDetails($month, $year)
    {
        return Order::with('Customer')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->select(
                'orders.*',
                DB::raw('COUNT(order_items.id) as total_items_count'),
                DB::raw('SUM(order_items.qty) as total_qty_sum')
            )
            ->whereMonth('orders.order_date', $month)
            ->whereYear('orders.order_date', $year)
            ->where('orders.is_completed', 1)
            ->groupBy('orders.id')
            ->orderBy('orders.order_date', 'desc')
            ->get();
    }




    //// បន្ថែម Methods ផ្សេងៗទៀតសម្រាប់ RepostRepository នៅទីនេះ
    public function getSalesByCategory(int $year): Collection
    {
        return DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->selectRaw('
                categories.name   AS cat_name,
                MONTH(orders.order_date) AS month,
                SUM(order_items.qty * order_items.price) AS total
            ')
            ->whereYear('orders.order_date', $year)
            ->where('orders.is_completed', 1)
            ->groupBy('cat_name', 'month')
            ->orderBy('cat_name')
            ->orderBy('month')
            ->get();
    }

    public function getMonthlySales(int $year): array
    {
        return DB::table('orders')
            ->whereYear('order_date', $year)
            ->where('is_completed', 1)
            ->selectRaw('MONTH(order_date) AS month, SUM(grand_total) AS total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    /**
     * Monthly COGS = SUM(qty * cost_price)
     */
    public function getMonthlyCOGS(int $year): array
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereYear('orders.order_date', $year)
            ->where('orders.is_completed', 1)
            ->selectRaw('
                MONTH(orders.order_date) AS month,
                SUM(order_items.qty * products.cost) AS total
            ')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    public function getMonthlyExpenses(int $year): array
    {
        return DB::table('expense_types')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) AS month, SUM(amount) AS total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    public function getMonthlyPurchaseCost(int $year): array
    {
        return DB::table('purchases')
            ->whereYear('purchase_date', $year)
            ->where('status', 'received')
            ->selectRaw('MONTH(purchase_date) AS month, SUM(grand_total) AS total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    public function getTopProducts(int $year, int $limit = 10): Collection
    {
        return DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereYear('orders.order_date', $year)
            ->where('orders.is_completed', 1)
            ->selectRaw('
                products.name AS product_name,
                SUM(order_items.qty) AS total_qty,
                SUM(order_items.qty * order_items.price) AS total_revenue
            ')
            ->groupBy('product_name')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }

    public function getAvailableYears(): array
    {
        return DB::table('orders')
            ->where('is_completed', 1)
            ->selectRaw('YEAR(order_date) AS year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
    }
}
