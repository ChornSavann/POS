<?php
namespace App\Repository;
use App\Models\Units;
use App\Repository\IRepository\IUnitsRepository;
class UnitRepository implements IUnitsRepository
{
    public function all()
    {
        return Units::all();
    }

    public function find($id)
    {
        return Units::findOrFail($id);
    }
    
    public function create(array $data)
    {
        return Units::create($data);
    }
    
    public function update($id, array $data)
    {
        $unit = Units::findOrFail($id);
        $unit->update($data);
        return $unit;
    }
    
    public function delete($id)
    {
        $unit = Units::findOrFail($id);
        return $unit->delete();
    }
    public function getBaseUnits()
    {
        return Units::whereNull('baseunit_id')->get();
    }
}