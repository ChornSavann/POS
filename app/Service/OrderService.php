<?php
namespace App\Service;

use App\Models\Seller;
use App\Models\Stores;
use App\Service\IService\IOrderService;
use App\Repository\IRepository\IOrderRepository;
use Illuminate\Support\Facades\DB;
class OrderService implements IOrderService {
    protected $orderRepo;
    public function __construct(IOrderRepository $orderRepo) {
        $this->orderRepo = $orderRepo;
    }

    public function getListOrderData($request) {
        $pageSize     = $request->get('pageSize', 100);
        $orders       = $this->orderRepo->getAllOrders($pageSize);
        $totalSales   = $this->orderRepo->getTotalSales();
        $totalDebt    = $this->orderRepo->getTotalDebt();
        // Logic គណនា
        $totalCollected = $totalSales - $totalDebt;
        return [
            'orders'         => $orders,
            'totalSales'     => $totalSales,
            'totalDebt'      => $totalDebt,
            'totalCollected' => $totalCollected
        ];
    }
    public function getProducts() {
        return $this->orderRepo->getAllProducts();
    }

    public function getSellers() {
        return $this->orderRepo->getAllSellers();
    }

    public function getCustomers() {
        return $this->orderRepo->getAllCustomers();
    }

    public function getCategories() {
        return $this->orderRepo->getAllCategories();
    }

    public function getTables() {
        return $this->orderRepo->getAllTables();
    }

    public function getBank()
    {
        return $this->orderRepo->getBank();
    }
    public function generateInvoice(array $orderData, array $cartItems) {
        return DB::transaction(function () use ($orderData, $cartItems) {
            // ១. បង្កើត Order មេ
            $order = $this->orderRepo->createOrder($orderData);

            // ២. បង្កើត Item លម្អិត និងកាត់ស្តុក
            foreach ($cartItems as $item) {
                $this->orderRepo->createOrderItem([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                    'discount'   => $item['discount'] ?? 0,
                    'total'      => $item['qty'] * $item['price']
                ]);

                // កាត់ស្តុកផលិតផល
                $this->orderRepo->updateProductStock($item['id'], $item['qty']);
            }

            return $order;
        });
    }
    private function generateInvoiceNumber()
    {
        $lastInvoice = $this->orderRepo->getLastInvoiceNo();
        if (!$lastInvoice) {
            return 'INV-00001';
        }
        $parts = explode('-', $lastInvoice);
        $lastNumString = end($parts);
        $nextNumber = (int)$lastNumString + 1;
        return 'INV-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    // public function processCheckOut(array $data) {
    //     return DB::transaction(function () use ($data) {
    //         // ១. រៀបចំតម្លៃសម្រាប់ Order
    //        $invoiceNo = $this->generateInvoiceNumber();
    //         $subTotal = $data['subtotal'] ?? 0;
    //         $discountAmount = $data['discount'] ?? 0;
    //         $grandTotal = $subTotal - $discountAmount;

    //         // កំណត់តម្លៃជំពាក់ និងស្ថានភាពជំពាក់
    //         $isCredit = (isset($data['is_credit']) && $data['is_credit'] == 1);
    //         $debtAmount = $data['debt_amount'] ?? 0;

    //         // ២. បង្កើត Order មេ
    //         $order = $this->orderRepo->createOrder([
    //             'invoice_no'     => $invoiceNo,
    //             'order_date'     => now(),
    //             'table_id'       => $data['table_id'],
    //             'customer_id'    => $data['customer_id'] ?? 1,
    //             'sub_total'      => $subTotal,
    //             'discount'       => $data['bank_discount_rate'] ?? 0,
    //             'total_discount' => $discountAmount,
    //             'tax'            => $data['tax_rate'] ?? 0,
    //             'grand_total'    => $grandTotal,
    //             'is_credit'      => $isCredit,
    //             'debt_amount'    => $debtAmount, // ✅ បន្ថែមការកត់ត្រាលុយជំពាក់នៅទីនេះ
    //             'is_completed'   => true,
    //             'is_paid'        => ($debtAmount <= 0), // បើអត់មានលុយជំពាក់ ទើបចាត់ទុកថា Paid
    //             'note'           => $data['note'] ?? null,
    //             'seller_id'      => $data['seller_id'] ?? 1,
    //             'store_id'       => 1,
    //         ]);

    //         // ៣. បង្កើត Items និង កាត់ស្តុក
    //         foreach ($data['items'] as $item) {
    //             $itemDiscount = $item['discount'] ?? 0;
    //             $itemTotal = ($item['price'] * $item['qty']) - $itemDiscount;

    //             $this->orderRepo->createOrderItem([
    //                 'order_id'   => $order->id,
    //                 'product_id' => $item['id'],
    //                 'qty'        => $item['qty'],
    //                 'price'      => $item['price'],
    //                 'discount'   => $itemDiscount,
    //                 'total'      => $itemTotal,
    //             ]);

    //             $this->orderRepo->updateProductStock($item['id'], $item['qty']);
    //         }

    //         // ៤. បង្កើត Payment
    //         $receivedUSD = $data['received_usd'] ?? 0;
    //         $receivedRiel = $data['received_riel'] ?? 0;
    //         $exchangeRate = 4100;
    //         $paidTotalAmount = $receivedUSD + ($receivedRiel / $exchangeRate);

    //         $this->orderRepo->createPayment([
    //             'order_id'       => $order->id,
    //             'payment_date'   => now(),
    //             'payment_method' => $data['payment_method'] ?? 'Cash',
    //             'paid_dollar'    => $receivedUSD,
    //             'paid_riel'      => $receivedRiel,
    //             'exchange_rate'  => $exchangeRate,
    //             'paid_amount'    => $paidTotalAmount,
    //             'balance_after'  => $data['balance_dollar'] ?? 0, // លុយអាប់
    //             'payment_status' => ($debtAmount > 0) ? 'Partial' : 'Completed', // បើនៅខ្វះលុយ ដាក់ថា Partial
    //             'payment_ref'    => $data['payment_ref'] ?? null,
    //             'note'           => $data['note'] ?? null,
    //         ]);

    //         // // ៥. ប្ដូរស្ថានភាពតុ
    //         if (!empty($data['table_id'])) {
    //             $this->orderRepo->updateTableStatus($data['table_id'], 'free');
    //         }
    //         return $order;
    //     });
    // }
    public function processCheckOut(array $data)
    {
        return DB::transaction(function () use ($data) {
            // ១. គណនា Discount សរុបពី Items (Item Level Discount)
            $totalItemDiscount = 0;
            foreach ($data['items'] as $item) {
                $totalItemDiscount += $item['discount'] ?? 0;
            }

            // ២. រៀបចំតម្លៃសម្រាប់ Order
            $invoiceNo = $this->generateInvoiceNumber();
            $subTotal = $data['subtotal'] ?? 0;

            // discount នេះគឺតម្លៃដែលចុះបន្ថែមលើ Invoice (ឧទាហរណ៍៖ ចុះថែម $2 ចុងក្រោយ)
            $invoiceDiscount = $data['discount'] ?? 0;

            // ✅ Total Discount = Discount លើទំនិញទាំងអស់ + Discount លើវិក្កយបត្រ
            $totalDiscountCalculated = $totalItemDiscount + $invoiceDiscount;

            // Grand Total ត្រូវដក Discount សរុបចេញ
            $grandTotal = $subTotal - $totalDiscountCalculated;

            // កំណត់តម្លៃជំពាក់
            $isCredit = (isset($data['is_credit']) && $data['is_credit'] == 1);
            $debtAmount = $data['debt_amount'] ?? 0;

            // ៣. បង្កើត Order មេ
            $order = $this->orderRepo->createOrder([
                'invoice_no'     => $invoiceNo,
                'order_date'     => now(),
                'table_id'       => $data['table_id'],
                'customer_id'    => $data['customer_id'] ?? 1,
                'sub_total'      => $subTotal,
                'discount'       => $invoiceDiscount,       // Discount បន្ថែម
                'total_discount' => $totalDiscountCalculated, // ✅ ឥឡូវវាបូកបញ្ចូលគ្នាទាំង Item និង Invoice Discount
                'tax'            => $data['tax_rate'] ?? 0,
                'grand_total'    => $grandTotal,
                'is_credit'      => $isCredit,
                'debt_amount'    => $debtAmount,
                'is_completed'   => true,
                'is_paid'        => ($debtAmount <= 0),
                'note'           => $data['note'] ?? null,
                'seller_id'      => $data['seller_id'] ?? 1,
                'store_id'       => 1,
            ]);

            // ៤. បង្កើត Items និង កាត់ស្តុក
            foreach ($data['items'] as $item) {
                $itemDiscount = $item['discount'] ?? 0;
                $itemTotal = ($item['price'] * $item['qty']) - $itemDiscount;

                $this->orderRepo->createOrderItem([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                    'discount'   => $itemDiscount,
                    'total'      => $itemTotal,
                ]);

                $this->orderRepo->updateProductStock($item['id'], $item['qty']);
            }

            // --- ផ្នែក Payment រក្សាទុកដដែល ---
            $receivedUSD = $data['received_usd'] ?? 0;
            $receivedRiel = $data['received_riel'] ?? 0;
            $exchangeRate = 4100;
            $paidTotalAmount = $receivedUSD + ($receivedRiel / $exchangeRate);

            $this->orderRepo->createPayment([
                'order_id'       => $order->id,
                'payment_date'   => now(),
                'payment_method' => $data['payment_method'] ?? 'Cash',
                'paid_dollar'    => $receivedUSD,
                'paid_riel'      => $receivedRiel,
                'exchange_rate'  => $exchangeRate,
                'paid_amount'    => $paidTotalAmount,
                'balance_after'  => $data['balance_dollar'] ?? 0,
                'payment_status' => ($debtAmount > 0) ? 'Partial' : 'Completed',
                'payment_ref'    => $data['payment_ref'] ?? null,
                'note'           => $data['note'] ?? null,
            ]);

            if (!empty($data['table_id'])) {
                $this->orderRepo->updateTableStatus($data['table_id'], 'free');
            }

            return $order;
        });
    }
    public function changeTableStatus($tableId, $status)
    {
        $formattedStatus = strtolower($status);
        return $this->orderRepo->updateTableStatus($tableId, $formattedStatus);
    }
    public function payDebt(array $data)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'order_id'   => 'required|exists:orders,id',
            'pay_amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()->first()];
        }

        try {
            return DB::transaction(function () use ($data) {
                // ២. ទាញយក Order និងចាក់សោរ Row (Pessimistic Locking)
                $order = \App\Models\Order::lockForUpdate()->find($data['order_id']);

                if (!$order) {
                    return ['success' => false, 'message' => 'រកមិនឃើញវិក្កយបត្រឡើយ!'];
                }

                $payAmount = $data['pay_amount'];

                // ឆែកក្រែងលោបង់លុយលើសចំនួនជំពាក់
                if ($payAmount > ($order->debt_amount + 0.01)) {
                    return ['success' => false, 'message' => 'ចំនួនលុយបង់លើសពីចំនួនជំពាក់!'];
                }
                // ៣. បន្ថយចំនួនលុយជំពាក់
                $order->debt_amount -= $payAmount;
                // ឆែកមើលថាបង់ដាច់ឬនៅ
                if ($order->debt_amount <= 0.005) {
                    $order->debt_amount = 0;
                    $order->is_paid = true;
                }
                $order->save();
                // ៤. កត់ត្រាចូលក្នុងតារាង Payments
                // គណនាដើម្បីប្រាកដថា បើទិន្នន័យបោះមកទទេ ឬស្មើសូន្យ ឱ្យវាគណនាតាមអត្រាប្តូរប្រាក់
                $paidUSD = (isset($data['received_usd']) && $data['received_usd'] > 0)
                            ? $data['received_usd']
                            : $payAmount;

                $paidRiel = (isset($data['received_riel']) && $data['received_riel'] > 0)
                            ? $data['received_riel']
                            : ($paidUSD * 4100);

                DB::table('order_payments')->insert([
                    'order_id'       => $order->id,
                    'payment_date'   => now(),
                    'payment_method' => $data['payment_method'] ?? 'CASH',
                    'paid_dollar'    => $paidUSD,
                    'paid_riel'      => $paidRiel,
                    'exchange_rate'  => 4100,
                    'paid_amount'    => $payAmount,
                    'payment_status' => 'Completed',
                    'note'           => $data['note'] ?? 'បង់បង្គ្រប់បំណុល',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                return ['success' => true];
            });
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

   // OrderService.php
    public function getPrintData($id)
    {
        $order = $this->orderRepo->getOrderForPrint($id);
        $store = \App\Models\Stores::first();

        return [
            'order'       => $order,
            'store'       => $store, // បោះ Store ទៅទាំងមូលដើម្បីយក ឈ្មោះ អាសយដ្ឋាន និង Logo
            'rate'        => 4100,
            // ប្រសិនបើ Table Order មាន seller_id បងអាចហៅតាម relation បែបនេះ៖
            'cashierName' => $order->seller->name ?? 'Admin',
        ];
         dd($store);
    }

    public function getDataForPrint()
    {
        return [
            'store'  => Stores::first(),
            'orders' => $this->orderRepo->getAllOrdersForPrint()
        ];
    }
}

