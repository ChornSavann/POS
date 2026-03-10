<?php
namespace App\Service\IService;
interface IUnitService {
    public function getAllUnits();
    public function getBaseUnits();
    public function getUnitDetails($id);
    public function storeUnit(array $data);
    public function editUnit($id, array $data);
    public function removeUnit($id);
}