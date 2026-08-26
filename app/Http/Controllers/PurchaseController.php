<?php

namespace App\Http\Controllers;

use App\Request\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Stores;
use App\Service\IService\IPurchaseService; // ប្រើ IPurchaseService ឱ្យត្រូវនឹង Service ដែលយើងបង្កើតមិញ
use Illuminate\Support\Facades\View;

class PurchaseController extends Controller
{
    protected $purchaseService;
    public function __construct(IPurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function getItemsFromPurchase(Request $request)
    {
        $purchase = Purchase::with(['items.product.unit'])
            ->where('reference_no', $request->ref_no)
            ->first();

        if (!$purchase) {
            return response()->json(['message' => 'រកមិនឃើញវិក្កយបត្រនេះទេ'], 404);
        }

        // រៀបចំទិន្នន័យឱ្យត្រូវជាមួយ Table បាកូដ
        $formattedItems = $purchase->items->map(function ($item) {
            return [
                'id'       => $item->product_id,
                'name'     => $item->product->name,
                'barcode'  => $item->product->barcode,
                'price'    => $item->unit_price, // តម្លៃលក់ចេញ
                'qty'      => $item->quantity,   // ចំនួនដែលបានទិញចូល
                'unitName' => $item->product->unit->name ?? 'Unit',
            ];
        });

        return response()->json($formattedItems);
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'per_page']);
        $purchases = $this->purchaseService->getAllPurchase($filters);

        return view('purchase.index', compact('purchases'));
    }


    public function create()
    {
        $suppliers = Supplier::all();
        $stores = Stores::all();
        $sellers = Seller::all(); // ទាញអ្នកលក់ពីតារាង users
        $products = Product::with('unit')->get();

        return view('purchase.create', compact('suppliers', 'stores', 'sellers', 'products'));
    }

    // public function store(PurchaseRequest $request)
    // {
    //     $purchase = $this->purchaseService->createPurchase($request->validated());
    //     return response()->json(['success' => true, 'data' => $purchase]);
    // }
    public function store(PurchaseRequest $request)
    {
        try {
            $purchase = $this->purchaseService->createPurchase($request->validated());
            return response()->json(['success' => true, 'data' => $purchase]);
        } catch (\Exception $e) {
            // បង្ហាញ Error ចេញមកក្រៅចំៗ ដើម្បីងាយស្រួលមើល
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
    
            ], 500);
        }
    }

    public function edit($id)
    {
        $purchase = Purchase::with('items.product.unit')->findOrFail($id);
        $suppliers = Supplier::all();
        $stores = Stores::all();
        $sellers = Seller::all(); // ទាញអ្នកលក់ពីតារាង users
        $products = Product::with('unit')->get();

        return view('purchase.edit', compact('purchase', 'suppliers', 'stores', 'sellers', 'products'));
    }
    public function update(PurchaseRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $this->purchaseService->updatePurchase($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'វិក្កយបត្រត្រូវបានកែប្រែដោយជោគជ័យ!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'កំហុស៖ ' . $e->getMessage()
            ], 500);
        }
    }

   public function destroy($id)
    {
        $this->purchaseService->deletePurchase($id);

        return redirect()
            ->route('purchases.index')
            ->with('success', 'លុបការទិញបានជោគជ័យ');
    }
}
