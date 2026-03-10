<?php

namespace App\Http\Controllers;

use App\Service\IService\IProductService;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $productService;
    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        // ១. ទាញទិន្នន័យទំនិញជិតអស់ស្តុកពី Service (Data ពិតដែលបងមានស្រាប់)
        $lowStockData = $this->productService->getLowStockProducts();
        // ២. ទាញទិន្នន័យ Revenue តាមខែ សម្រាប់ឆ្នាំបច្ចុប្បន្ន (Sales Chart)
        $currentYear = Carbon::now()->year;
        $salesData = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(grand_total) as total') // សន្មតថាបងមាន column grand_total
            )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // រៀបចំ Array ១២ ខែ (Jan - Dec) ឱ្យមានតម្លៃ 0 ជាមុន
        $salesValues = array_fill(0, 12, 0);
        $salesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach ($salesData as $data) {
            $salesValues[$data->month - 1] = (float)$data->total;
        }

        // ៣. ទាញទិន្នន័យផលិតផលលក់ដាច់បំផុត Top 5 (Pie Chart)
        // យើង Join ជាមួយ Table products ដើម្បីយកឈ្មោះ (name) មកបង្ហាញ
        $topProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name as product_name',
                DB::raw('SUM(order_items.qty) as total_qty')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $topProductNames = $topProducts->pluck('product_name')->toArray();
        $topProductQty = $topProducts->pluck('total_qty')->toArray();

        return view('component.dashboard', [
            'lowStockProducts' => $lowStockData['lowStockProducts'],
            'lowStockCount'    => $lowStockData['lowStockCount'],
            'SalesLabels'      => $salesLabels,
            'SalesValues'      => $salesValues,
            'TopProductNames'  => $topProductNames,
            'TopProductQty'    => $topProductQty
        ]);
    }
}
