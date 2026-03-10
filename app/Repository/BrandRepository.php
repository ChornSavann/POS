<?php
namespace App\Repository;
use App\Models\Brand;
use App\Repository\IRepository\IBrandRepository;
use Illuminate\Support\Facades\File;

class BrandRepository implements IBrandRepository{
    
    public function all($perPage = 10, array $filters = [])
    {
        $query = Brand::query();
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function find($id)
    {
        return Brand::findOrFail($id);
    }

    public function create(array $data)
    {
        return Brand::create($data);
    }

    public function update($id, array $data)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($data);
        return $brand;
    }

    public function delete($id)
    {
        $brand = Brand::findorFail($id);
        if (!$brand) return false;
        // លុបរូបភាពចេញពី Storage
        $imagePath = public_path('Image/brands/' . $brand->image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        return $brand->delete();
    }
}
