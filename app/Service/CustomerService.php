<?php
namespace App\Service;
use App\Repository\IRepository\ICustomerRepository;
use App\Service\IService\ICustomerService;
class CustomerService implements ICustomerService
{
    protected $customerRepository;

    public function __construct(ICustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getallCustomer()
    {
        return $this->customerRepository->all(10);
    }

    public function getByid($id)
    {
        return $this->customerRepository->find($id);
    }

    public function createCustomer(array $data)
    {
        return $this->customerRepository->create($data);
    }

    public function updateCustomer($id, array $data)
    {
        return $this->customerRepository->update($id, $data);
    }

    public function deleteCustomer($id)
    {
        return $this->customerRepository->delete($id);
    }
}