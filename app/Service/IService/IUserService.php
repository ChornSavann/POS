<?php
namespace App\Service\IService;
interface IUserService
{
    public function getAllUsers();
    public function getUserById($id);
    public function createUser(array $data);
    public function updateUser($id, array $data);
    public function deleteUser($id);
    public function login(array $credentials);
}