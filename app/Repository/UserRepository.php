<?php
namespace App\Repository;
use App\Models\User;
use App\Repository\IRepository\IUserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
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
  
   // UserRepository.php
    public function login(array $credentials)
    {
        // Auth::attempt នឹងឆែក Password ផង និងបង្កើត Session ឱ្យ User ជាប់ក្នុង System ផង
        return Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ], request()->has('remember'));
    }
}
