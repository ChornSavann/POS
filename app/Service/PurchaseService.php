<?php
namespace App\Service;
use App\Service\IService\IPurchaseService;
use App\Repository\IRepository\IPurchaseRepository;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class PurchaseService implements IPurchaseService {
    protected $purchaseRepo;

    public function __construct(IPurchaseRepository $purchaseRepo) {
        $this->purchaseRepo = $purchaseRepo;
    }

    public function getAllPurchase() {
        return $this->purchaseRepo->all();
    }

    public function getById($id) {
        return $this->purchaseRepo->find($id);
    }
    public function createPurchase(array $data)
    {
        return DB::transaction(function () use ($data) {

            // 1️⃣ Create Purchase
            $purchase = $this->purchaseRepo->create([
                'reference_no'  => $data['reference_no'],
                'purchase_date' => $data['purchase_date'],
                'supplier_id'   => $data['supplier_id'],
                'store_id'      => $data['store_id'],
                'seller_id'     => $data['seller_id'],
                'status'        => $data['status'],
                'grand_total'   => $data['grand_total'],
                'note'          => $data['note'] ?? null,
            ]);

            // 2️⃣ Loop Items
            foreach ($data['items'] as $item) {

                $purchase->items()->create([
                    'product_id' => $item['productId'],
                    'quantity'   => $item['qty'],
                    'unit_cost'  => $item['unitCost'],
                    'unit_price' => $item['unitPrice'],
                    'discount'   => $item['discount'] ?? 0,
                    'subtotal'   => ($item['unitCost'] - ($item['discount'] ?? 0)) * $item['qty'],
                ]);

                // 3️⃣ Update Stock if Received
                if ($data['status'] === 'Received') {

                    // Update or Create Stock
                    $stock = Stock::firstOrCreate(
                        ['product_id' => $item['productId']],
                        ['qty' => 0, 'note' => 'Initial Stock']
                    );

                    $stock->increment('qty', $item['qty']);

                    // 4️⃣ Log Stock Movement
                    StockMovement::create([
                        'product_id' => $item['productId'],
                        'type'       => 'IN', // Stock In
                        'qty'        => $item['qty'],
                        'reference'  => $purchase->reference_no,
                        'note'       => 'Purchase Received',
                    ]);
                }
            }

            return $purchase;
        });
    }
    public function updatePurchase($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $purchase = Purchase::findOrFail($id);

            // 1️⃣ Rollback old stock if old status was 'Received'
            if ($purchase->status === 'Received') {
                foreach ($purchase->items as $oldItem) {
                    $stock = Stock::where('product_id', $oldItem->product_id)->first();
                    if ($stock) {
                        $stock->decrement('qty', $oldItem->quantity);
                    }

                    // Log stock OUT
                    StockMovement::create([
                        'product_id' => $oldItem->product_id,
                        'type'       => 'OUT',
                        'qty'        => $oldItem->quantity,
                        'reference'  => $purchase->reference_no,
                        'note'       => 'Purchase updated: rollback old stock'
                    ]);
                }
            }

            // 2️⃣ Delete old items
            $purchase->items()->delete();

            // 3️⃣ Update master purchase
            $purchase->update([
                'reference_no'  => $data['reference_no'],
                'purchase_date' => $data['purchase_date'],
                'supplier_id'   => $data['supplier_id'],
                'store_id'      => $data['store_id'],
                'seller_id'     => $data['seller_id'],
                'status'        => $data['status'],
                'grand_total'   => $data['grand_total'],
                'note'          => $data['note'] ?? null,
            ]);

            // 4️⃣ Add new items and update stock if status is 'Received'
            if ($data['status'] === 'Received') {
                foreach ($data['items'] as $item) {
                    // 4a. Create purchase item
                    $purchase->items()->create([
                        'product_id' => $item['productId'],
                        'quantity'   => $item['qty'],
                        'unit_cost'  => $item['unitCost'],
                        'unit_price' => $item['unitPrice'],
                        'discount'   => $item['discount'] ?? 0,
                        'subtotal'   => ($item['unitCost'] - ($item['discount'] ?? 0)) * $item['qty'],
                    ]);

                    // 4b. Update stock
                    $stock = Stock::firstOrCreate(
                        ['product_id' => $item['productId']],
                        ['qty' => 0, 'note' => 'Purchase Stock']
                    );
                    $stock->increment('qty', $item['qty']);

                    // 4c. Log stock IN
                    StockMovement::create([
                        'product_id' => $item['productId'],
                        'type'       => 'IN',
                        'qty'        => $item['qty'],
                        'reference'  => $purchase->reference_no,
                        'note'       => 'Purchase updated: new stock added'
                    ]);
                }
            }

            return $purchase;
        });
    }
    public function deletePurchase($id)
    {
        return DB::transaction(function () use ($id) {

            $purchase = $this->purchaseRepo->find($id);

            if (!$purchase) {
                throw new \Exception("Purchase not found");
            }

            $purchase->items()->delete();

            return $this->purchaseRepo->delete($id);
        });
    }




}
