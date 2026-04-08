<?php

namespace App\Http\Controllers;
use App\Service\IService\IOrderService;
use Illuminate\Http\Request;
use App\Models\Order;


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


    public function checkOut(Request $request)
    {
        // ១. ឆែកមើលជាមុនសិនថា តើ md5 នេះធ្លាប់បានបង្កើត Order រួចហើយឬនៅ? (ការពារការផ្ញើមកស្ទួន)
        if ($request->filled('md5')) {
            $existingOrder = Order::where('payment_md5', $request->md5)->first();
            if ($existingOrder) {
                return response()->json([
                    'success' => true,
                    'order_id' => $existingOrder->id,
                    'message' => 'ការបង់ប្រាក់ត្រូវបានកត់ត្រារួចហើយ!'
                ]);
            }
        }

        // ២. ករណីបង់តាម QR (មាន md5)
        if ($request->filled('md5')) {
            try {
                $token = env('BAKONG_TOKEN');
                $bakong = new \KHQR\BakongKHQR($token);

                // ឆែកមើលក្នុងប្រព័ន្ធបាគង
                $result = $bakong->checkTransactionByMD5($request->md5);

                if (isset($result['responseCode']) && $result['responseCode'] == 0) {
                    // ប្រសិនបើបាគងបញ្ជាក់ថាបានទទួលលុយមែន ទើបបង្កើត Order
                    // ចំណាំ៖ កុំភ្លេចថែម 'payment_md5' ទៅក្នុង orderService ផង
                    $order = $this->orderService->processCheckOut($request->all());
                    return response()->json([
                        'success' => true,
                        'order_id' => $order->id,
                        'message' => 'ការបង់ប្រាក់តាម QR ជោគជ័យ!'
                    ]);
                }

                // បើមិនទាន់ឃើញលុយចូល
                return response()->json([
                    'success' => false,
                    'message' => 'កំពុងរង់ចាំការបាញ់លុយ...'
                ]);

            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }

        // ៣. ករណីបង់ "លុយសុទ្ធ" (Cash)
        try {
            $order = $this->orderService->processCheckOut($request->all());
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'រក្សាទុកការបង់លុយសុទ្ធជោគជ័យ!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function showInvoice($id) {
        // ហៅ Service តែមួយជួររួចរាល់!
        $data = $this->orderService->getInvoiceData($id);

        return view('order.invoice', [
            'order' => $data['order'],
            'shopSetting' => $data['shopSetting'], // បោះទៅ View
            'qr' => $data['qr'],
            'md5' => $data['md5']
        ]);
    }
}
