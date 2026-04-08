<?php

namespace App\Service;

use App\Repository\IRepository\IUserRepository;
use App\Service\IService\IUserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
            // ១. Hash password
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // ២. រៀបចំរូបភាព (Profile Picture) ដោយប្រើ move() ទៅកាន់ public_path
            if (isset($data['profile_picture']) && $data['profile_picture'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $data['profile_picture'];
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('Image/users-image');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                // បញ្ចូល File ទៅក្នុង Folder ផ្ទាល់ (Direct Upload)
                $file->move($destinationPath, $fileName);
                $data['profile_picture'] = $fileName;
            }
            // ៣. កំណត់ Status លំនាំដើមបើមិនមានបញ្ជូនមក
            $data['is_active'] = $data['is_active'] ?? 1;
            return $this->userRepository->createUser($data);

        } catch (Exception $e) {
            Log::error("Error creating user: " . $e->getMessage());
            return null;
        }
    }

    public function registerUser(array $data)
    {
        $userData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => $data['password'], // កុំទាន់ Hash នៅទីនេះ
            'role'      => $data['role'] ?? 'admin',
            'phone'     => $data['phone'] ?? null,
            'address'   => $data['address'] ?? null,
            'is_active' => true,
        ];

        $user = $this->userRepository->register($userData);

        if ($user) {
            Auth::login($user);
        }

        return $user;
    }

    public function updateUser($id, array $data)
    {
        try
        {
            $user = $this->userRepository->getUserById($id);
            // Logic Password
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            // Logic សម្រាប់រូបភាព (ប្រើ move ទៅ public_path)
            if (isset($data['profile_picture']) && $data['profile_picture'] instanceof \Illuminate\Http\UploadedFile) {

                $file = $data['profile_picture'];
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('Image/users-image'); // កំណត់ Path ទៅ Folder public/Image/users-image
                // លុបរូបចាស់ចេញពី Folder public ផ្ទាល់
                if ($user->profile_picture && file_exists($destinationPath . '/' . $user->profile_picture)) {
                    unlink($destinationPath . '/' . $user->profile_picture);
                }
                // បញ្ចូល File ទៅកាន់ Folder គោលដៅ
                $file->move($destinationPath, $fileName);
                $data['profile_picture'] = $fileName;
            }

            return $this->userRepository->updateUser($id, $data);
        }
        catch (Exception $e) {
            Log::error("Error updating user ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    public function deleteUser($id)
    {
        try
        {
            $user = $this->userRepository->getUserById($id);
            if ($user)
            {
                $imagePath = public_path('Image/users-image/' . $user->profile_picture);
                if ($user->profile_picture && file_exists($imagePath)) {
                    unlink($imagePath);
                }
                return $this->userRepository->deleteUser($id);
            }
            return false;
        }
        catch (Exception $e) {
            Log::error("Error deleting user ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function login(array $credentials)
    {
        return $this->userRepository->login($credentials);
    }
}
