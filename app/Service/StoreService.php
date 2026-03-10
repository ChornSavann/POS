<?php
namespace App\Service;
use App\Repository\IRepository\IStoreRepository;
use App\Service\IService\IStoreService;
use Illuminate\Support\Str;
 use Illuminate\Support\Facades\File;
class StoreService implements IStoreService {
    protected $storeRepository;

    public function __construct(IStoreRepository $storeRepository) {
        $this->storeRepository = $storeRepository;
    }

    public function getallStore($request) {
        return $this->storeRepository->all($request);
    }

    public function getbyidStore($id) {
        return $this->storeRepository->find($id);
    }

   public function createStore(array $data) {
        $data['slug'] = \Str::slug($data['name']);
        if (request()->hasFile('logo')) {
            $image = request()->file('logo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('Image/stores');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            
            $image->move($path, $imageName);
            $data['logo'] = $imageName; 
        }

        return $this->storeRepository->create($data);
    }
  

    public function updateStore($id, array $data) {
        $store = $this->storeRepository->find($id);
        $data['slug'] = \Str::slug($data['name']);
        if (request()->hasFile('logo')) {
            $oldImagePath = public_path('Image/stores/' . $store->logo);
            if (File::exists($oldImagePath) && !empty($store->logo)) {
                File::delete($oldImagePath);
            }
            $image = request()->file('logo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Image/stores'), $imageName);
            $data['logo'] = $imageName;
        }

        return $this->storeRepository->update($id, $data);
    }

    public function deleteStore($id) {
        $store = $this->storeRepository->find($id);
        if ($store && !empty($store->logo)) {
            $imagePath = public_path('Image/stores/' . $store->logo);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        return $this->storeRepository->delete($id);
    }
}