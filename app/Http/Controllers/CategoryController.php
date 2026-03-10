<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Request\CategoryRequest;


class CategoryController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('category.index', compact('categories'));
    }
    public function create()
    {
        return view('category.create');
    }
    public function store(CategoryRequest $request){
        $category = $this->categoryService->storeCategory($request->validated());
        if ($category) {
            // dd($category);
            return redirect()->route('category.index')->with('success', 'Category created successfully!');
        }
        return back()->with('error', 'Something went wrong while creating the category.');
    }
    public function edit($id)
    {
        $category = $this->categoryService->getCategoryDetails($id);
        return view('category.edit', compact('category'));
    }
    public function update(CategoryRequest $request, $id)
    {
        $category = $this->categoryService->editCategory($id, $request->validated());
        if ($category) {
            return redirect()->route('category.index')->with('success', 'Category updated successfully!');  
        }
        return back()->with('error', 'Something went wrong while updating the category.');
    }
    public function destroy($id)
    {
        $deleted = $this->categoryService->removeCategory($id);
        if ($deleted) {
            return redirect()->route('category.index')->with('success', 'Category deleted successfully!');
        }
        return back()->with('error', 'Something went wrong while deleting the category.');
    }
}
