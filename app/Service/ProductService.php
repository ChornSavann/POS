<?php
namespace App\Service;

use App\Service\IService\IProductService;
use App\Repository\IRepository\IProductRepository;
use Illuminate\Support\Collection;
class ProductService implements IProductService {

    protected $productRepo;

    public function __construct(IProductRepository $productRepo) {
        $this->productRepo = $productRepo;
    }

    public function getFormData() {
        return [
            'categories' => $this->productRepo->getCategory(),
            'brands'     => $this->productRepo->getBrand(),
            'units'      => $this->productRepo->getUnit(),
            'stocks'     => $this->productRepo->getStock(),
        ];
    }

    public function getAllProducts() {
        return $this->productRepo->all();
    }

    // 2️⃣ Get low-stock products and count
    public function getLowStockProducts($threshold = 15)
    {
        $allProducts = $this->productRepo->getLowStockProducts();
        $lowStockProducts = $allProducts->filter(function ($p) use ($threshold) {
                return optional($p->stock)->qty < $threshold;
        });
        $lowStockCount = $lowStockProducts->count();
        return [
            'products'         => $allProducts,      // all products
            'lowStockProducts' => $lowStockProducts, // only low stock
            'lowStockCount'    => $lowStockCount
        ];
    }

    public function storeProduct(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile)
        {
            $file = $data['image'];
            // បង្កើតឈ្មោះ File ថ្មីដើម្បីកុំឱ្យជាន់គ្នា (ឧទាហរណ៍: 171583200.jpg)
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('Image/products'), $fileName);
             $data['image'] = 'Image/products/' . $fileName;
        }
        return $this->productRepo->create($data);
    }

   public function updateProduct($id, array $data)
   {

        $product = $this->productRepo->find($id);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $file = $data['image'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('Image/products'), $fileName);
            $data['image'] = 'Image/products/' . $fileName;
        }
        $data['status'] = isset($data['status']) ? 1 : 0;

        return $this->productRepo->update($id, $data);
    }

    public function deleteProduct(array $ids)
    {
                foreach ($ids as $id) {
            $product = $this->productRepo->find($id);
            if ($product) {
                // លុបរូបភាព
                if ($product->image && file_exists(public_path($product->image))) {
                    @unlink(public_path($product->image));
                }
                // លុបទិន្នន័យ
                $product->delete();
            }
        }
        return true;
    }

    public function findProduct($id) {
        return $this->productRepo->find($id);
    }



    public function getProductSuggestions(string $term)
    {
        $products = $this->productRepo->searchSuggestions($term);

        return $products->map(fn($p) => [
            'id'       => $p->id,
            'name'     => $p->name,
            'barcode'  => $p->barcode,
            'price'    => $p->price,
            'unitName' => $p->unit->name ?? 'Unit',
        ]);
    }
}
