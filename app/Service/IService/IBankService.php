<?php

namespace App\Service\IService;
interface IBankService
{
    public function getAllBank($request);

    public function createBank(array $data);

    public function getByIdBank($id);

    public function updateBank($id, array $data);

    public function deleteBank($id);
}
