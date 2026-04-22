<?php

namespace App\Http\Controllers;
use App\Service\IService\IOrderService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Service\TelegramService;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\StockMovement;

class OrderController extends Controller
{

    protected $orderService;
    protected $telegramService;

    public function __construct(IOrderService $orderService, TelegramService $telegramService) {
        $this->orderService = $orderService;
        $this->telegramService = $telegramService;
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
                    $order = $this->orderService->processCheckOut($request->all());
                    // ── Telegram ──────────────────────────────────
                    // $this->telegramService->sendOrderNotification($order, 'KHQR / ABA');
                   $receivedUSD = (float) $request->input('received_amount', $order->grand_total);
                    $changeUSD   = $receivedUSD > $order->grand_total ? $receivedUSD - $order->grand_total : 0;
                    $this->telegramService->sendOrderNotification($order, 'KHQR / ABA', $receivedUSD, $changeUSD);
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
            $paymentInfo = $order->payments()->with('bank')->first();
            $bankName = $paymentInfo?->bank?->bank_name;

            if (!$bankName) {
                $method = $paymentInfo?->payment_method ?? 'Cash';
                // បើ $method ជាលេខ ID ឱ្យបង្ហាញថា 'Cash' ឬ 'បង់តាមធនាគារ' ជំនួសវិញដើម្បីកុំឱ្យអាក្រក់មើល
                $bankName = is_numeric($method) ? 'Cash' : $method;
            }

            // ៣. គណនាប្រាក់ទទួលបាន និងប្រាក់អាប់
            $grandTotal  = (float)$order->grand_total;
            $receivedUSD = $paymentInfo ? (float)$paymentInfo->paid_amount : $grandTotal;
            $changeUSD   = ($receivedUSD > $grandTotal) ? round($receivedUSD - $grandTotal, 2) : 0;

            // ៤. បញ្ជូនទៅកាន់ TelegramService
            $this->telegramService->sendOrderNotification(
                $order,
                $bankName,
                $paymentInfo,
                $receivedUSD,
                $changeUSD
            );

            return response()->json([
                'success'  => true,
                'order_id' => $order->id,
                'message'  => 'ការបង់ប្រាក់ត្រូវបានរក្សាទុក និងផ្ញើដំណឹងរួចរាល់!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហា៖ ' . $e->getMessage()
            ], 500);
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

    public function cancelOrder($id)
    {
        // ១. ទាញយកទិន្នន័យ (Eager Loading)
        // ប្រាកដថាឈ្មោះក្នុង with() ដូចទៅនឹងឈ្មោះ function ក្នុង Model Order
        $order = Order::with(['orderItems', 'payments'])->findOrFail($id);

        if ($order->is_completed === 0 || $order->is_completed === '0') {
            return back()->with('error', 'Order នេះត្រូវបាន Cancel រួចរាល់ហើយ។');
        }

        try {
            DB::beginTransaction();

            // ២. Update Order (ចំណុចសំខាន់៖ មិនគួរ Update store_id ឬ customer_id ទៅជា 0 ទេ
            // ទុកវាឱ្យនៅដដែល ដើម្បីដឹងថា Order ហ្នឹងមកពីសាខាណា ឬជារបស់ភ្ញៀវណា គ្រាន់តែប្តូរ status បានហើយ)
            $order->update([
                'is_completed'   => 2, // សម្គាល់ថា Order នេះត្រូវបាន Cancel
                'store_id'       => $order->store_id, // ទុកដដែល
                'customer_id'    => $order->customer_id, // ទុកដដែល
                'table_id'       => 0, // បោះតុចេញ
                'is_paid'        => 2, // សម្គាល់ថា Order នេះត្រូវបាន Cancel (អាចប្រើ -1 ឬ 'cancelled' ជាដើម)
                'debt_amount'    => 0,
                'sub_total'      => 0,
                'grand_total'    => 0,
                'total_discount' => 0,
                'note'           => $order->note . " | Cancelled on " . now()->format('Y-m-d H:i:s'),
            ]);

            // ៣. Update Payments
            foreach ($order->payments as $payment) {
                $payment->update([
                    'paid_dollar'    => 0,
                    'paid_riel'      => 0,
                    'paid_amount'    => 0,
                    'payment_status' => 'refunded',
                    'note'           => 'បង្វិលសងវិញដោយសារការ Cancel Order'
                ]);
            }

            // ៤. ប្តូរពី $order->items ទៅជា $order->orderItems ឱ្យដូចខាងលើ
            foreach ($order->orderItems as $item) {
                $item->update([
                    'price'    => 0,
                    'qty'      => 0,
                    'discount' => 0,
                    'total'    => 0
                ]);
                // បូកស្តុក
                $stock = Stock::where('product_id', $item->product_id)->first();

                if ($stock) {
                    $stock->increment('qty', $item->qty);
                } else {
                    Stock::create([
                        'product_id' => $item->product_id,
                        'qty'        => $item->qty,
                        'note'       => 'Stock added from cancelled order #' . $order->invoice_no
                    ]);
                }

                // កត់ត្រា Movement
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'type'       => 'IN',
                    'qty'        => $item->qty,
                    'reference'  => 'CANCEL-' . $order->invoice_no,
                    'note'       => 'Re-stocked from Cancelled Order'
                ]);
            }

            DB::commit();
            return back()->with('success', 'ការ Cancel Order និងបូកស្តុកត្រឡប់វិញបានជោគជ័យ!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'មានបញ្ហា៖ ' . $e->getMessage());
        }
    }
}
