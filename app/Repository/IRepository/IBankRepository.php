<?php
namespace App\Repository\IRepository;
interface IBankRepository{
    public function all($request);
    public function create(array $data);
    public function find($id);
    public function update($id,array $data);
    public function delete($id);
}
