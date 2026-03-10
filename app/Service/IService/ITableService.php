<?php
namespace App\Service\IService;
interface ITableService{
    public function getAllTable();
    public function createTable(array $data);
    public function updateTable(array $data,$id);
    public function deleteTable($id);
}
