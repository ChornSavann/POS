<?php
namespace App\Repository;

use App\Models\Product;
use App\Models\Units;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Stock;
use App\Repository\IRepository\IProductRepository;

class ProductRepository implements IProductRepository {

    protected $model;

    public function __construct(Product $product) {
        $this->model = $product;
    }

    public function getUnit() {
        return Units::all();
    }

    public function getCategory() {
        return Category::where('status', 1)->get();
    }

    public function getBrand() {
        return Brand::where('status', 1)->get();
    }

    public function getStock()
    {
        return Stock::all();
    }

    public function all()
    {
        $query = \App\Models\Product::query();
        // ស្វែងរកតាមឈ្មោះ ឬ Barcode
        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        }
        // ចម្រោះតាម Category
        if (request()->has('category') && request('category') != '') {
            $query->where('category_id', request('category'));
        }

        // ចម្រោះតាម Brand
        if (request()->has('brand') && request('brand') != '') {
            $query->where('brand_id', request('brand'));
        }

        return $query->with(['category', 'brand', 'unit'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10); // បងអាចប្រើ get() ឬ paginate()
    }

   public function getLowStockProducts()
    {
        return Product::with(['unit', 'stock'])->get();
    }

    public function find($id) {
        return $this->model->findOrFail($id);
    }

    public function create(array $data) {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }


    public function searchSuggestions($term, $limit = 10)
    {
        return $this->model->where(function($query) use ($term) {
                $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('barcode', 'LIKE', "%{$term}%");
            })
            ->with('unit') // Eager Loading ដើម្បីកុំឱ្យយឺត (N+1 Problem)
            ->limit($limit)
            ->get();
    }
}
