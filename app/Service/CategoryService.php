<?php
namespace App\Service;

use App\Service\IService\ICategoryService;
use App\Repository\IRepository\ICategoryRepository;

class CategoryService implements ICategoryService
{
    protected $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }

    public function getCategoryDetails($id)
    {
        return $this->categoryRepository->find($id);
    }
    
    public function storeCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }
    
    public function editCategory($id, array $data)
    {
        return $this->categoryRepository->update($id, $data);
    }
    
    public function removeCategory($id)
    {
        return $this->categoryRepository->delete($id);
    }
}