<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\IService\ISellerService;
use App\Request\SellerRequest;
class SellerController extends Controller
{
    protected $sellerService;
    public function __construct(ISellerService $sellerService)
    {
        $this->sellerService = $sellerService;
    }

    public function index()
    {
        $sellers = $this->sellerService->getAllSeller();
        return view('seller.index', compact('sellers'));
    }
    public function create()
    {
        return view('seller.create');
    }
    public function store(SellerRequest $request)
    {
        $this->sellerService->createSeller($request);
        return redirect()->route('seller.index')->with('success', 'Seller created successfully.');
    }
    public function edit($id)
    {
        $seller = $this->sellerService->getByid($id);
        return view('seller.edit', compact('seller'));
    }
    public function update(SellerRequest $request, $id)
    {
        $this->sellerService->updateSeller($request, $id);
        return redirect()->route('seller.index')->with('success', 'Seller updated successfully.');
    }
    public function destroy($id)
    {
        $this->sellerService->deleteSeller($id);
        return redirect()->route('seller.index')->with('success', 'Seller deleted successfully.');
    }

}
