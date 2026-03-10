<?php

namespace App\Http\Controllers;

use App\Request\ItemExpenseRequest; // កែ Namespace ឱ្យត្រូវតាម Laravel Standard
use App\Service\IService\IItemExpenseService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// បន្ថែមអក្សរ e ទៅក្នុង ItemExpenseController
class ItemExpenseController extends Controller
{
    protected $service;

    public function __construct(IItemExpenseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $itemExpenses = $this->service->getAll($request);
        return view('item_expense.index', compact('itemExpenses'));
    }

    public function create()
    {
        return view('item_expense.create');
    }

    public function store(ItemExpenseRequest $request)
    {
        $data = $request->validated();
        $this->service->createItemExpense($data);

        return redirect()->route('item_expense.index')
            ->with('success', 'ការចំណាយត្រូវបានរក្សាទុកដោយជោគជ័យ!');
    }

    public function edit($id)
    {
        $itemExpense = $this->service->findItemExpense($id);
        return view('item_expense.edit', compact('itemExpense'));
    }

    public function update(ItemExpenseRequest $request, $id)
    {
        $data = $request->validated();
        $this->service->updateItemExpense($id, $data);

        return redirect()->route('item_expense.index')
            ->with('success', 'ការចំណាយត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    public function destroy($id)
    {
        $this->service->deleteItemExpense($id);
        return redirect()->route('item_expense.index')
            ->with('success', 'ទិន្នន័យត្រូវបានលុបចេញពីប្រព័ន្ធ!');
    }
}
