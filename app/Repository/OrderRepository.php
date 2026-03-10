<?php
namespace App\Repository;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Tables;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\Stores;
use App\Repository\IRepository\IOrderRepository;
use Illuminate\Session\Store;

class OrderRepository implements IOrderRepository {
    public function getLastInvoiceNo()
    {
        return Order::orderBy('id', 'desc')->value('invoice_no');
    }
    public function getAllProducts()
    {
        return Product::with('category')->get();
    }

    public function getAllCustomers() {
        return Customer::all();
    }

    public function getAllSellers() {
        return User::all();
    }

    public function getAllTables() {
        return Tables::all();
    }

    public function getBank()
    {
        return Bank::all();
    }
    public function getAllCategories() {
        return Category::has('products')->get();
    }

    public function createOrder(array $data) {
        return Order::create($data);
    }

    public function createOrderItem(array $data) {
        return OrderItem::create($data);
    }


    public function updateProductStock($productId, $qty)
    {
        // Find stock by product_id
        $stock = Stock::where('product_id', $productId)->first();
        if ($stock) {
            $stock->decrement('qty', $qty);
            StockMovement::create([
                'product_id' => $productId,
                'type'       => 'OUT',
                'qty'        => $qty,
                'reference'  => null, // Optional: invoice/order number
                'note'       => 'Stock reduced after order checkout'
            ]);
        }
        else
        {
            // If no stock record exists, create one with negative qty
            $stock = Stock::create([
                'product_id' => $productId,
                'qty'        => 0 - $qty,
                'note'       => 'Stock adjustment after order'
            ]);

            StockMovement::create([
                'product_id' => $productId,
                'type'       => 'OUT',
                'qty'        => $qty,
                'reference'  => null,
                'note'       => 'Stock reduced after order (new stock record created)'
            ]);
        }

        return true;
    }
    public function updateTableStatus($tableId, $status)
    {
        if (!$tableId) return false;

        $table = Tables::find($tableId);
        if ($table) {
            return $table->update([
                'status'     => $status, // ត្រូវប្រាកដថា 'Free' នេះត្រូវជាមួយ CSS ក្នុង View
                'updated_at' => now()
            ]);
        }
        return false;
    }


    public function createPayment(array $data)
    {
        return OrderPayment::create($data);
    }

    public function getAllOrders($pageSize) {
        return Order::with(['customer', 'table'])
                    ->latest('order_date')
                    ->paginate($pageSize);
    }

    public function getTotalSales() {
        return Order::sum('grand_total');
    }

    public function getTotalDebt() {
        return Order::sum('debt_amount');
    }


    public function getOrderForPrint($id)
    {
        return Order::with(['orderItems.product', 'customer', 'seller']) // ថែម seller ត្រង់នេះ
                    ->findOrFail($id);
    }

    public function getAllOrdersForPrint()
    {
        return Order::with(['orderItems.product', 'customer', 'seller'])
                    ->orderBy('order_date', 'desc')
                    ->get();
    }
}
