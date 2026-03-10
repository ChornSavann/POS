<?php

namespace App\Service;

use App\Repository\IRepository\IUserRepository;
use App\Service\IService\IUserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class UserService implements IUserService {
    
    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function getUserById($id)
    {
        return $this->userRepository->getUserById($id);
    }

    public function createUser(array $data)
    {
        try {
           
            // Business Logic: Hash password មុនរក្សាទុក
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // កំណត់ Role លំនាំដើមបើគ្មានការបញ្ជូនមក
            $data['role'] = $data['role'] ?? 'user';

            return $this->userRepository->createUser($data);
        } catch (Exception $e) {
            Log::error("Error creating user: " . $e->getMessage());
            return null;
        }
    }

    public function updateUser($id, array $data)
    {
        try 
        {
            // Business Logic: បើដូរ password ត្រូវ Hash តែបើអត់ទេ ត្រូវលុបវាចេញដើម្បីកុំឱ្យវា Update ជាន់ password ចាស់
            if (!empty($data['password'])) 
            {
                $data['password'] = Hash::make($data['password']);
            } 
            else 
                {
                unset($data['password']);
            }

            return $this->userRepository->updateUser($id, $data);
        } 
        catch (Exception $e) 
        {
            Log::error("Error updating user ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    public function deleteUser($id)
    {
        return $this->userRepository->deleteUser($id);
    }

    public function login(array $credentials)
    {
        // មុខងារ Login តាមរយៈ Service Layer
        return $this->userRepository->login($credentials);
    }
}