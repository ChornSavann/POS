<?php

namespace App\Http\Controllers;

use App\Models\Tables;
use App\Service\IService\IOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    protected $orderService;

    public function __construct(IOrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function listOrder(Request $request)
    {
       $data = $this->orderService->getListOrderData($request);
        return view('order.list', $data);
    }
    public function payDebt(Request $request)
    {
        $result = $this->orderService->payDebt($request->all());
        return response()->json($result);
    }
    public function index() {
        // Get all products with stock relationship
        $allProducts = $this->orderService->getProducts()->load('stock');
        $products = $allProducts->filter(function($p) {
            return $p->stock && $p->stock->qty > 0;
        });
        $formattedProducts = $products->map(function($p) {
            return [
                'id'       => $p->id,
                'name'     => $p->name,
                'barcode'  => $p->barcode,
                'price'    => (float)$p->price,
                'stock'    => (int)($p->stock->qty ?? 0),
                'discount' => (float)($p->discount ?? 0)
            ];
        });
        return view('order.index', [
            'products'          => $products,
            'formattedProducts' => $formattedProducts,
            'customers'         => $this->orderService->getCustomers(),
            'tables'            => $this->orderService->getTables(),
            'categories'        => $this->orderService->getCategories(),
            'banks'             =>$this->orderService->getBank()
        ]);
    }

    public function updateTableStatus(Request $request)
    {
       $result = $this->orderService->changeTableStatus(
            $request->tableId,
            $request->status
        );

        if ($result) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'រកមិនឃើញតុ ឬមិនអាច Update បាន'
        ], 404);
    }


    public function checkOut(Request $request) {
        try {
            $order = $this->orderService->processCheckOut($request->all());

            return response()->json([
                'success' => true,
                'message' => 'ការទូទាត់ជោគជ័យ!',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function showInvoice($id)
    {
        $order = Order::with(['customer', 'orderItems.product'])->findOrFail($id);
        return view('order.invoice', compact('order'));
    }

    public function printInvoice($id) {
        $data = $this->orderService->getPrintData($id);
        return view('order.invoice_sale', $data);
    }

   
    public function printAll()
    {
        // ហៅទិន្នន័យពី Service
        $data = $this->orderService->getDataForPrint();
        return view('order.print_all', $data);
    }
}
