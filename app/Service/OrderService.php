<?php
namespace App\Service;

use App\Models\CashSession;

use App\Models\Stores;
use App\Service\IService\IOrderService;
use App\Repository\IRepository\IOrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use KHQR\Helpers\KHQRData;
use KHQR\BakongKHQR;
use KHQR\Helpers\Utils;
use App\Libraries\KHQRTimestampFix;
use Illuminate\Support\Facades\Auth;
use KHQR\Models\IndividualInfo;
// use SimpleSoftwareIO\QrCode\Facades\QrCode; // សម្រាប់បង្កើតរូបភាព QR
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;
class OrderService implements IOrderService {
    protected $orderRepo;
    public function __construct(IOrderRepository $orderRepo) {
        $this->orderRepo = $orderRepo;
    }

    public function getListOrderData($request) {
        $pageSize     = $request->get('pageSize', 25);
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

    public function getActiveSession(): ?CashSession
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return $this->orderRepo->findActiveSessionByUser($user->id);
    }
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
            $grandTotal = $subTotal ;

            // កំណត់តម្លៃជំពាក់
            $isCredit = (isset($data['is_credit']) && $data['is_credit'] == 1);
            $debtAmount = $data['debt_amount'] ?? 0;
            // $activeSession = CashSession::where('user_id', auth()->id())->where('status', 'open')->first();
             $activeSession = $this->getActiveSession();
            // ៣. បង្កើត Order មេ
            $order = $this->orderRepo->createOrder([
                'invoice_no'     => $invoiceNo,
                'order_date'     => now(),
                'cash_session_id' => $activeSession->id, // ត្រូវតែបញ្ចូល ID នេះ
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


    // public function getPrintData($id)
    // {
    //     $order = $this->orderRepo->getOrderForPrint($id);
    //     $store = \App\Models\Stores::first();
    //     $cashierName = $order->seller->name ?? 'Admin';

    //     return response()->json([
    //         'order' => $order,
    //         'store' => $store,
    //         'cashierName' => $cashierName,
    //         'exchangeRate' => 4100,
    //     ]);
    // }

    public function getPrintData($id)
    {
        $order = $this->orderRepo->getOrderForPrint($id);
        $store = \App\Models\Stores::first();
         $qrData = $this->generateKHQR($order->grand_total);
        $cashierName = Auth::user()->name ?? 'Admin';

        // ✅ កែមកជា Array ធម្មតា (លុប response()->json ចេញ)
        return [
            'order' => $order,
            'store' => $store,
            'cashierName' => $cashierName,
            'exchangeRate' => 4100,
             'qr' => $qrData['qr'],
            'md5' => $qrData['md5']
        ];
    }

    public function getDataForPrint()
    {
        return [
            'store'  => Stores::first(),
            'orders' => $this->orderRepo->getAllOrdersForPrint()
        ];
    }



    public function getInvoiceData($id) {
        $order = $this->orderRepo->getOrderForInvoice($id);
        $shopSetting = $this->orderRepo->getShopSetting(); // ទាញទិន្នន័យហាងចេញពី DB
        $qrData = $this->generateKHQR($order->grand_total);

        return [
            'order' => $order,
            'shopSetting' => $shopSetting, // បោះទៅឱ្យ View
            'qr' => $qrData['qr'],
            'md5' => $qrData['md5']
        ];
    }

    // public function generateKHQR($grandTotal, $exchangeRate = 4100)
    // {
    //     $amountInRiel = round($grandTotal * $exchangeRate);

    //     $bakongId = "chorn_savann@bkrt";
    //     $merchantName = "SAVANN CHORN";
    //     $city = "Phnom Penh";

    //     $baidTag = "00" . str_pad(strlen($bakongId), 2, '0', STR_PAD_LEFT) . $bakongId;
    //     $merchantInfo = "30" . str_pad(strlen($baidTag), 2, '0', STR_PAD_LEFT) . $baidTag;

    //     $rawData = "000201010212" . $merchantInfo;
    //     $rawData .= "520459995303116";
    //     $rawData .= "54" . str_pad(strlen($amountInRiel), 2, '0', STR_PAD_LEFT) . $amountInRiel;
    //     $rawData .= "5802KH";
    //     $rawData .= "59" . str_pad(strlen($merchantName), 2, '0', STR_PAD_LEFT) . $merchantName;
    //     $rawData .= "60" . str_pad(strlen($city), 2, '0', STR_PAD_LEFT) . $city;
    //     $rawData .= "6304";

    //     $crc = $this->calculateCRC16($rawData);
    //     $qrRawData = $rawData . $crc;

    //     $qr = null;
    //     if ($qrRawData) {
    //         $qr = QrCode::format('svg')
    //             ->size(120)
    //             ->color(0, 90, 146)
    //             ->margin(1)
    //             ->generate($qrRawData);
    //     }
    //     // dd($qrRawData, $crc);
    //     return [
    //         'qr' => $qr,
    //         'md5' => md5($qrRawData)
    //     ];
    // }


    // public function generateKHQR($grandTotal, $exchangeRate = 4100)
    // {
    //         $amountInRiel = intval(round($grandTotal * $exchangeRate));

    //             $individualInfo = new IndividualInfo(
    //             bakongAccountID: 'chorn_savann@bkrt',
    //             merchantName: 'SAVANN CHORN',
    //             merchantCity: 'Phnom Penh',
    //             currency: KHQRData::CURRENCY_KHR,
    //             amount: $amountInRiel, // ← static QR មិនមាន expiration
    //             // amount: 0,
    //         );

    //     $response = BakongKHQR::generateIndividual($individualInfo);
    //     // dd($response->data['qr']); // ← បន្ថែម នេះ
    //             if ($response->status['code'] !== 0) {
    //         throw new \Exception('KHQR Error: ' . $response->status['message']);
    //     }

    //     $qrString = $response->data['qr'];

    //     $qr = QrCode::format('svg')
    //         ->size(120)
    //         ->color(0, 90, 146)
    //         ->margin(1)
    //         ->generate($qrString);

    //     return [
    //         'qr'  => $qr,
    //         'md5' => $response->data['md5'],
    //     ];
    // }






public function generateKHQR($grandTotal, $exchangeRate = 4100)
{
    $amountInRiel = (int)(round((float)$grandTotal * (float)$exchangeRate));

    $individualInfo = new IndividualInfo(
       bakongAccountID: 'chorn_savann@bkrt',
        merchantName: 'SAVANN CHORN',
        merchantCity: 'Phnom Penh',
        currency: KHQRData::CURRENCY_KHR,
        amount: $amountInRiel,
    );

    $response = BakongKHQR::generateIndividual($individualInfo);
// $check = BakongKHQR::checkBakongAccount('chorn_savann@bkrt');
// dd($check->status, $check->data);
    if ($response->status['code'] !== 0) {
        throw new \Exception('KHQR Error: ' . $response->status['message']);
    }

    $raw      = $response->data['qr'];
    $pos      = strpos($raw, '9917');
    $fixed    = substr($raw, 0, $pos) . str_replace(' ', '', substr($raw, $pos));
    $noCrc    = substr($fixed, 0, -4);
    $qrString = $noCrc . \KHQR\Helpers\Utils::crc16($noCrc);
// dd($qrString);
    $options = new QROptions;
    $options->outputType = QRGdImagePNG::class;
    $options->scale      = 8;
    $options->imageBase64 = true;

    $qr = (new QRCode($options))->render($qrString);

    return [
        'qr'  => $qr,
        'md5' => md5($qrString),
    ];
}


    // private function calculateCRC16($data) {
    //     $crc = 0xFFFF;
    //     for ($i = 0; $i < strlen($data); $i++) {
    //         $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
    //         $x ^= $x >> 4;
    //         $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
    //     }
    //     return strtoupper(str_pad(dechex($crc & 0xFFFF), 4, '0', STR_PAD_LEFT));
    // }




}

