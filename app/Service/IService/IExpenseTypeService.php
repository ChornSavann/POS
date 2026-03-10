<?php
namespace App\Service\IService;
interface IExpenseTypeService{
    public function getAllExpenseTypes($request);
    public function FormData();
    public function createExpenseType(array $data);
    public function getExpenseTypeById($id);
    public function updateExpenseType($id, array $data);
    public function deleteExpenseType($id);
}
