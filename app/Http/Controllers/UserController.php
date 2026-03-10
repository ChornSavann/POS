<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\IService\IUserService;
use Illuminate\Support\Facades\Log;
use App\Request\UserRequest;

class UserController extends Controller
{
    protected $userService;

    // Inject IUserService តាមរយៈ Constructor
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function login()
    {
        return view('users.login');
    }
   
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        // data is already clean here
        $user = $this->userService->createUser($request->validated());

        if ($user) {
            return redirect()->route('users.index')->with('success', 'User created successfully!');
        }
        return back()->with('error', 'Something went wrong while creating the user.');
    }

   
    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        return view('users.edit', compact('user'));
    }

   
    public function update(UserRequest $request, $id)
    {
        $result = $this->userService->updateUser($id, $request->validated());

        if ($result) {
            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        }
        return back()->with('error', 'Update failed.');
    }

   
    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            Log::error("Delete User Error: " . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Cannot delete user.');
        }
    }
}