<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\IService\IUnitService;
use App\Request\UnitRequest;
use App\Models\Unit;

class UnitController extends Controller
{
    private $unitService;
    public function __construct(IUnitService $unitService)
    {
        $this->unitService = $unitService;
    }
    
    public function index()
    {
        $units = $this->unitService->getAllUnits();
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $baseUnits = $this->unitService->getBaseUnits();
        return view('units.create', compact('baseUnits'));
    }
    public function store(UnitRequest $request)
    {
        $this->unitService->storeUnit($request->validated());
        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }
    public function edit($id)
    {
        $unit = $this->unitService->getUnitDetails($id);
        $baseUnits = $this->unitService->getBaseUnits();
        return view('units.edit', compact('unit', 'baseUnits'));
    }

    public function update(UnitRequest $request, $id)
    {
        $this->unitService->editUnit($id, $request->validated());
        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }
}
