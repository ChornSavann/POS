<?php

namespace App\Http\Controllers;

use App\Service\IService\IBrandService;
use App\Request\BrandRequest;
use Illuminate\Http\Request; // បន្ថែមសម្រាប់ index method

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(IBrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * បង្ហាញបញ្ជី Brand (ប្រើ Request ធម្មតាសម្រាប់ Filter)
     */
    public function index(Request $request)
    {
        $brands = $this->brandService->getAllBrands($request);
        return view('brand.index', compact('brands'));
    }

    public function create()
    {
        return view('brand.create');
    }

    /**
     * រក្សាទុក Brand ថ្មី
     */
    public function store(BrandRequest $request)
    {
        // បញ្ជូន $request ទៅកាន់ Service (ព្រោះក្នុង Service បងប្រើ $request->hasFile...)
        $this->brandService->storeBrand($request);

        return redirect()->route('brand.index')
            ->with('success', 'ម៉ាកយីហោត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    public function edit($id)
    {
        $brand = $this->brandService->getBrandDetails($id);

        if (!$brand) {
            return redirect()->route('brand.index')->with('error', 'រកមិនឃើញទិន្នន័យឡើយ!');
        }

        return view('brand.edit', compact('brand'));
    }

    /**
     * កែសម្រួល Brand
     */
    public function update(BrandRequest $request, $id)
    {
        // បញ្ជូន $id និង $request ទៅកាន់ Service
        $result = $this->brandService->updateBrand($id, $request);

        if (!$result) {
            return back()->with('error', 'ការកែសម្រួលមានបញ្ហា!');
        }

        return redirect()->route('brand.index')
            ->with('success', 'ម៉ាកយីហោត្រូវបានធ្វើបច្ចុប្បន្នភាពជោគជ័យ!');
    }

    public function destroy($id)
    {
        $this->brandService->removeBrand($id);

        return redirect()->route('brand.index')
            ->with('success', 'ម៉ាកយីហោត្រូវបានលុបចេញពីប្រព័ន្ធ!');
    }
}
