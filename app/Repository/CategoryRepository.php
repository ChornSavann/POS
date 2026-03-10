<?php
namespace App\Repository;

use App\Models\Category;
use App\Repository\IRepository\ICategoryRepository;

class CategoryRepository implements ICategoryRepository
{
    // កែពី getAllCategories ទៅជា all តាម Interface
    public function all()
    {
        return Category::all();
    }

    // កែពី getCategoryById ទៅជា find តាម Interface
    public function find($id)
    {
        return Category::findOrFail($id);
    }
    
    // កែពី createCategory ទៅជា create តាម Interface
    public function create(array $data)
    {
        return Category::create($data);
    }
    
    // កែពី updateCategory ទៅជា update តាម Interface
    public function update($id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }
    
    // កែពី deleteCategory ទៅជា delete តាម Interface
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        return $category->delete();
    }
}