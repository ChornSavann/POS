<?php
namespace App\Repository\IRepository;
interface IExpenseTypeRepository{
    public function all($request);
    public function bank();
    public function itemExpense();
    public function store(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
}
