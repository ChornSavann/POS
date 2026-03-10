<?php

namespace App\Service\IService;

interface IItemExpenseService
{
    public function getAll($request);

    public function createItemExpense(array $data);

    public function findItemExpense($id);

    public function updateItemExpense($id, array $data);

    public function deleteItemExpense($id);
}
