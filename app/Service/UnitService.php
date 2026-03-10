<?php
namespace App\Service;
use App\Service\IService\IunitService;
use App\Repository\IRepository\IUnitsRepository;
class UnitService implements IUnitService
{
    protected $unitRepository;

    public function __construct(IUnitsRepository $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    public function getAllUnits()
    {
        return $this->unitRepository->all();
    }

    public function getUnitDetails($id)
    {
        return $this->unitRepository->find($id);
    }
    public function getBaseUnits()
    {
        return $this->unitRepository->getBaseUnits();
    }
    public function storeUnit(array $data)
    {
        return $this->unitRepository->create($data);
    }
    
    public function editUnit($id, array $data)
    {
        return $this->unitRepository->update($id, $data);
    }
    
    public function removeUnit($id)
    {
        return $this->unitRepository->delete($id);
    }
}
