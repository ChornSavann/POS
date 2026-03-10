<?php
namespace App\Service\IService;
interface ICustomerService
{
    public function getallCustomer();
    public function getByid($id);
    public function createCustomer(array $data);
    public function updateCustomer($id, array $data);
    public function deleteCustomer($id);
}