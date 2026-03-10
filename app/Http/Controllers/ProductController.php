<?php

namespace App\Http\Controllers;
use App\Request\ProductRequest;
use App\Service\IService\IProductService; // ពិនិត្យ Namespace ឱ្យត្រូវនឹងអ្វីដែលបងបានបង្កើត
use App\Models\Units;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        $formData = $this->productService->getFormData();
        // $lowStockData = $this->productService->getLowStockProducts();

        return view('products.index', [
            'products'   => $products,
            // 'lowStockProducts'  => $lowStockData['lowStockProducts'], // only low stock
            // 'lowStockCount'     => $lowStockData['lowStockCount'],    // count for badge
            'categories' => $formData['categories'], // បន្ថែមនេះ
            'brands'     => $formData['brands'],
            'units'      => $formData['units'] ,// បន្ថែមនេះដើម្បីប្រើក្នុង Form/Modal    // បន្ថែមនេះ
            'stocks'     => $formData['stocks']
        ]);
    }
    public function getSubUnits($base_unit_id)
    {
        try
        {
            $units = Units::where('id', $base_unit_id)
                        ->orWhere('baseunit_id', $base_unit_id)
                        ->get();

            return response()->json($units);
        }
        catch (\Exception $e)
        {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function create()
    {
        $data = $this->productService->getFormData();
        return view('products.create', $data);
    }

    public function store(ProductRequest $request)
    {
        $this->productService->storeProduct($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'ទំនិញត្រូវបានបង្កើតដោយជោគជ័យ! ✨');
    }

    public function edit($id)
    {
        $product = $this->productService->findProduct($id);
        $formData = $this->productService->getFormData();

        return view('products.edit', array_merge(['product' => $product], $formData));
    }

    public function update(ProductRequest $request, $id)
    {
        $data['status'] = $request->has('status') ? 1 : 0; // បង្ខំម្តងទៀតនៅទីនេះ
        $this->productService->updateProduct($id, $request->validated());
        return redirect()->route('products.index')
            ->with('success', 'ទិន្នន័យទំនិញត្រូវបានកែប្រែរួចរាល់! ✅');
    }

    public function destroy(Request $request, $id = null)
    {
        $ids = $request->has('ids') ? $request->ids : [$id];

        try {
            $this->productService->deleteProduct($ids);

            // បើមកពី AJAX (Bulk Delete) ឱ្យបោះ JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'លុបទិន្នន័យបានជោគជ័យ! ✅'
                ]);
            }

            // បើមកពី Form Submit ធម្មតា (Single Delete) ឱ្យ Redirect ទៅ Index
            return redirect()->route('products.index')
                ->with('success', 'ទំនិញត្រូវបានលុបចេញពីប្រព័ន្ធ! 🗑️');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'មានបញ្ហា៖ ' . $e->getMessage());
        }
    }


    public function generateBarcode()
    {
        return view('products.printBarcode');
    }

    public function getSuggestions(Request $request)
    {
        // ដូរពី ProductRequest មក Request
        $suggestions = $this->productService->getProductSuggestions($request->term ?? '');
        return response()->json($suggestions);
    }
}

