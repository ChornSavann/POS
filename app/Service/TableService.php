<?php
namespace App\Service;

use App\Repository\IRepository\ITableRepository;
use App\Service\IService\ITableService;

class TableService implements ITableService
{
    private $tableRepository;

    public function __construct(ITableRepository $repository)
    {
        $this->tableRepository = $repository;
    }

    public function getAllTable()
    {
        return $this->tableRepository->all();
    }

    public function createTable(array $data)
    {
        return $this->tableRepository->create($data);
    }

    public function updateTable(array $data, $id)
    {
        return $this->tableRepository->update($id, $data);
    }

    public function deleteTable($id)
    {
        return $this->tableRepository->delete($id);
    }
}
