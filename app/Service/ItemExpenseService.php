<?php

namespace App\Service;

use App\Repository\IRepository\IItemExpenseRepository;
use App\Service\IService\IItemExpenseService;

class ItemExpenseService implements IItemExpenseService
{
    protected $repos;

    /**
     * ចាក់បញ្ចូល Repository តាមរយៈ Constructor (Dependency Injection)
     */
    public function __construct(IItemExpenseRepository $repos)
    {
        $this->repos = $repos;
    }
    public function getAll($request)
    {
        return $this->repos->all($request);
    }

    public function createItemExpense(array $data)
    {
        return $this->repos->create($data);
    }

    public function findItemExpense($id)
    {
        return $this->repos->find($id);
    }

    public function updateItemExpense($id, array $data)
    {
        return $this->repos->update($id, $data);
    }

    public function deleteItemExpense($id)
    {
        return $this->repos->delete($id);
    }
}
