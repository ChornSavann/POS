<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Stores;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    // public function Daily()
    // {
    //     $orders = Order::with(['orderItems.product', 'customer'])
    //         ->whereDate('order_date', now()->today())
    //         ->where('is_completed', true)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     // បន្ថែម Logic ការពារ Error sum() on null ក្នុង Controller តែម្តង
    //     return view('report.daily', compact('orders'));
    // }

    public function printInvoice($id)
    {
        // ១. ទាញយកព័ត៌មានហាង
        $storee = Stores::first();

        // ២. ទាញយក Order ជាមួយ Relations
        $order = Order::with(['customer', 'orderItems.product', 'seller'])->findOrFail($id);

        // ៣. បង្កើត Variable បន្ថែម
        $cashierName = $order->seller->name ?? 'Admin';
        $rate = 4100; // អត្រាប្តូរប្រាក់សម្រាប់បង្ហាញក្នុងវិក្កយបត្រ

        // ៤. បញ្ជូនទិន្នន័យទៅ View (ថែម $rate ចូលក្នុង compact)
        return view('order.invoice_sale', compact('order', 'storee', 'cashierName', 'rate'));
    }
    public function Daily()
    {
        // ទាញយក Order ទាំងអស់ប្រចាំថ្ងៃនេះ
        $orders = Order::whereDate('order_date', now()->toDateString())->get();

        // បូកសរុបតម្លៃសម្រាប់ Card ខាងលើ
        $totalOrders = $orders->count();

        // បូកសរុបចំនួនទំនិញដែលលក់ដាច់ (Item Qty)
        $totalItems = $orders->sum(function($order) {
            return $order->orderItems->sum('qty');
        });

        // ✅ បូកសរុប Discount ទាំងអស់ (ដែលយើងបាន Save ចូល total_discount ពីមុនមក)
        $totalDiscount = $orders->sum('total_discount');

        $totalSales = $orders->sum('sub_total');
        $netSales = $orders->sum('grand_total');

        return view('report.daily', compact(
            'orders',
            'totalOrders',
            'totalItems',
            'totalDiscount',
            'totalSales',
            'netSales'
        ));
    }
}
