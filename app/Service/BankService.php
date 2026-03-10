<?php

namespace App\Service;

use App\Repository\IRepository\IBankRepository;
use App\Service\IService\IBankService;

class BankService implements IBankService
{
    protected $repository;

    public function __construct(IBankRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllBank($request)
    {
        return $this->repository->all($request);
    }

    public function createBank(array $data)
    {
        return $this->repository->create($data);
    }

    public function getByIdBank($id)
    {
        return $this->repository->find($id);
    }

    public function updateBank($id, array $data)
    {
        $bank = $this->repository->find($id);

        if (!$bank) {
            return false;
        }

        return $this->repository->update($id, $data);
    }

    public function deleteBank($id)
    {
        $bank = $this->repository->find($id);

        if (!$bank) {
            return false;
        }

        return $this->repository->delete($id);
    }
}
