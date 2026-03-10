<?php
namespace App\Repository;
use App\Models\User;
use App\Repository\IRepository\IUserRepository;
use Illuminate\Support\Facades\Hash;
class UserRepository implements IUserRepository
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }
    
    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
    public function updateUser($id, array $data)
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return $user;
    }
    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        $user->delete();
        return true;
    }
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }
        return null;
    }
}