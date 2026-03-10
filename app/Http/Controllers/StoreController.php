<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\IService\IStoreService;
use App\Request\StoreRequest;
use App\Models\Stores;
use App\Repository\IRepository\IStoreRepository;

class StoreController extends Controller
{
    private $storeService;
    public function __construct(IStoreService $storeService) {
        $this->storeService = $storeService;
    }


    public function index(Request $request) {
        // បោះ $request ទៅកាន់ Service ដើម្បីឱ្យវាស្គាល់ពាក្យ Search ឬ Status Filter
        $stores = $this->storeService->getallStore($request);
        
        return view('store.index', compact('stores'));
    }
    public function create() {
        return view('store.create');
    }
   
    public function store(StoreRequest $request) {
        $this->storeService->createStore($request->all());
        return redirect()->route('store.index')->with('success', 'Store created successfully.');    
    }
   
    public function edit($id) {
        $store = $this->storeService->getbyidStore($id);
        return view('store.edit', compact('store'));
    }
    public function update(StoreRequest $request, $id) {
        $this->storeService->updateStore($id, $request->all());
        return redirect()->route('store.index')->with('success', 'Store updated successfully.');
    }
    public function destroy($id) {
        $this->storeService->deleteStore($id);
        return redirect()->route('store.index')->with('success', 'Store deleted successfully.');
    }
}
