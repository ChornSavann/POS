<?php
namespace App\Service\IService;
interface IUserService
{
    public function getAllUsers();
    public function getUserById($id);
    public function createUser(array $data);
    public function updateUser($id, array $data);
    public function DeleteUser($id);
    public function login(array $credentials);
    public function registerUser(array $data);
}
