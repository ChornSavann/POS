<?php

namespace App\Service;

use App\Repository\IRepository\IExpenseTypeRepository;
use App\Service\IService\IExpenseTypeService;

class ExpenseTypeService implements IExpenseTypeService
{
    protected $repository;

    public function __construct(IExpenseTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function FormData()
    {
        return [
            'banks'        =>$this->repository->bank(),
            'itemExpense'  =>$this->repository->itemExpense()
        ];
    }
    public function getAllExpenseTypes($request)
    {
        return $this->repository->all($request);
    }

    public function createExpenseType(array $data)
    {
        // អ្នកអាចបន្ថែម Logic ផ្សេងៗនៅទីនេះ (ឧទាហរណ៍៖ ការគណនាពន្ធ)
        return $this->repository->store($data);
    }

    public function getExpenseTypeById($id)
    {
        return $this->repository->find($id);
    }

    public function updateExpenseType($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteExpenseType($id)
    {
        return $this->repository->delete($id);
    }
}
