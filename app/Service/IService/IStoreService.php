<?php
namespace App\Service\IService;
interface IStoreService {
    public function getallStore($request);
    public function getbyidStore($id);
    public function createStore(array $data);
    public function updateStore($id, array $data);
    public function deleteStore($id);
}