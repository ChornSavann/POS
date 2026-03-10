<?php
namespace App\Service;

use App\Repository\IRepository\IBrandRepository;
use App\Service\IService\IBrandService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandService implements IBrandService {
    protected $brandRepository;
    protected $path;

    public function __construct(IBrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->path = public_path('Image/brands'); // កំណត់ Path តែម្ដងងាយស្រួលប្រើ
    }

    public function getAllBrands($request)
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
        ];
        return $this->brandRepository->all(10, $filters);
    }

    public function getBrandDetails($id)
    {
        return $this->brandRepository->find($id);
    }

    public function storeBrand($request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        return $this->brandRepository->create($data);
    }

    public function updateBrand($id, $request)
    {
        $brand = $this->brandRepository->find($id);
        if (!$brand) return false;

        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            // លុបរូបចាស់តាមរយៈ Private Method
            $this->deleteOldImage($brand->image);
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        return $this->brandRepository->update($id, $data);
    }

    public function removeBrand($id)
    {
        $brand = $this->brandRepository->find($id);
        if ($brand) {
            $this->deleteOldImage($brand->image);
            return $this->brandRepository->delete($id);
        }
        return false;
    }

    // --- Private Helper Methods ដើម្បីឱ្យកូដខាងលើមើលទៅ Soft ---

    private function uploadImage($file)
    {
        if (!File::exists($this->path)) {
            File::makeDirectory($this->path, 0755, true);
        }

        $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($this->path, $imageName);
        return $imageName;
    }

    private function deleteOldImage($imageName)
    {
        if (!empty($imageName)) {
            $fullPath = $this->path . '/' . $imageName;
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }
}
