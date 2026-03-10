<?php

namespace App\Http\Controllers;

use App\Request\BankRequest;
use App\Service\IService\IBankService; // ✅ correct spelling
use Illuminate\Http\Request;

class BankController extends Controller
{
    protected $bankService;

    public function __construct(IBankService $bankService) // ✅ match interface
    {
        $this->bankService = $bankService;
    }

    public function index(Request $request)
    {
        $banks = $this->bankService->getAllBank($request);

        return view('bank.index', compact('banks'));
    }

    public function create()
    {
        return view('bank.create');
    }

    public function store(BankRequest $request)
    {
        $this->bankService->createBank($request->validated());

        return redirect()
            ->route('bank.index')
            ->with('success', 'Bank created successfully.');
    }

    public function edit($id)
    {
        $bank = $this->bankService->getByIdBank($id);

        return view('bank.edit', compact('bank'));
    }

    public function update($id, BankRequest $request)
    {
        $this->bankService->updateBank($id, $request->validated());

        return redirect()
            ->route('bank.index')
            ->with('success', 'Bank updated successfully.');
    }

    public function destroy($id)
    {
        $this->bankService->deleteBank($id);

        return redirect()
            ->route('bank.index')
            ->with('success', 'Bank deleted successfully.');
    }
}
